<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class PaymentController extends Controller
{

    private $merchant_id = "cf170bc8-d29e-4476-88be-129ce61f70ee";

    private function pay(int $amount, int $order_id, string $mobile, string $email, string $callback_url)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sandbox.zarinpal.com/pg/v4/payment/request.json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "merchant_id": "' . $this->merchant_id . '",
                "amount": "' . $amount . '",
                "callback_url": "' . $callback_url . '", 
                "order_id" : "' . $order_id . '",    
                "description": "Transaction description.",
                "metadata": {
                  "mobile": "' . $mobile . '",
                  "email": "' . $email . '"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return \json_decode($response, true);
        } catch (\Exception $e) {
            return back()->with('fail', $e->getMessage());
        }
    }

    public function store(Request $request, ?Order $orderObj = null)
    {
        $user = Auth::user();
        if ($orderObj == null) {
            $validatedTotalPrice = $request->validate([
                'total_price' => ['required', 'numeric', 'min:10000'],
                'discountCode' => ['sometimes', 'exists:discount_codes,id']
            ]);
            $totalPrice = $validatedTotalPrice['total_price'];
            $order = $user->order()->firstOrCreate([
                'total_price' => $validatedTotalPrice['total_price'],
                'cart' => Cookie::get('basket'),
                'discount_code_id'=>$validatedTotalPrice['discountCode'] ?? null
            ]);
        } else {
            $order = $orderObj;
            $totalPrice = $order->total_price;
        }

        $response = $this->pay($totalPrice * 10, $order->id, $user->phone, $user->email, \route('payment.verify', $order->id));
        if (empty($response['errors'])) {
            if ($response['data']['code'] == 100) {
                return redirect()->away("https://sandbox.zarinpal.com/pg/StartPay/" . $response['data']["authority"]);
            }
        } else {
            return \view('payment.failed');
        }
    }


    private function verify(string $amount, string $authority)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
  "merchant_id": "' . $this->merchant_id . '",
  "amount": "' . $amount . '",
  "authority": "' . $authority . '"
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return \json_decode($response, true);
    }

    public function verifyPayment(Request $request, int $order_id)
    {
        if ($request->query('Status') == 'NOK') {
            return \view('payment.failed');
        }
        $order = Order::findOrFail($order_id);
        $verify = $this->verify($order->total_price * 10, $request->query('Authority'));
        if ($verify['data']['code'] == 100) {
            $order->status = 'paid';
            $order->save();
            $payment = $order->payment()->create([
                'ref_id' => $verify['data']['ref_id'],
            ]);
            $address = Auth::user()->userAddresses()->where('is_default', true)->first();
            if ($order->cart == Cookie::get('basket')) {
                Cookie::queue(Cookie::forget('basket'));
            }
            return \view('payment.success', ['order' => $order, 'payment' => $payment, 'address' => $address]);
        }
    }

    public function setDiscountCode(Request $request)
    {
        $code = DiscountCode::where('code', $request->input('code'))->where('is_active', true)->first();
        if (!$code) {
            return \back()->with('codeFail', 'کد تخفیف وارد شده معتبر نمی باشد');
        }
        return back()->with(['discountValue' => $code->discount_value, 'discountCode' => $code->id]);
    }

    public function deleteUserOrder(Order $order){
        $order->delete();
        return \redirect()->route('userdashboard.orders')->with('success','سفارش مورد نظر لغو شد');
    }

    public function deleteOrder(Order $order){
        $order->delete();
        return back()->with('success','سفارش مورد نظر لغو شد');
    }

    public function getOrders(Request $request){
        $orders = Order::query();
        if ($request->filled('order_id')) {
            $orders=$orders->where('id',$request->query('order_id'));
        }
        if ($request->filled('user')) {
            $userId=User::select('id')->where('name','like','%'.$request->query('user').'%');
            $orders=$orders->whereIn('user_id',$userId);
        }
        if ($request->filled('status')) {
            $orders=$orders->where('status',$request->query('status'));
        }
        $orders=$orders->get();
        return \view('payment.orders',['orders'=>$orders]);
    }
}
