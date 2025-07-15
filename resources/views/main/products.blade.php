@extends('layouts.main_layout')

@section('content')


<!-- Products Page Content -->
<div class="container py-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body filters-Sidebar">
                    <h5 class="mb-4">فیلتر محصولات</h5>

                    <form action="{{route('product.productFilters')}}" method="get">
                        <!-- Price Filter -->
                        <!-- Price Filter -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">محدوده قیمت</h6>
                            <div class="d-flex justify-content-between small">
                                <span id="lowerPriceValue">۰ تومان</span>
                                <span id="upperPriceValue">۱۰,۰۰۰,۰۰۰ تومان</span>
                            </div>
                            <div class="range-slider">
                                <input name="minPrice" type="range" class="custom-range" min="0" max="1000000" step="100" value="0" id="lowerPrice">
                                <input name="maxPrice" type="range" class="custom-range" min="0" max="10000000" step="100" value="10000000" id="upperPrice">
                            </div>
                            <div class="text-center mt-2">
                                <span class="badge badge-primary" id="priceRangeValue">۰ تا ۱۰,۰۰۰,۰۰۰ تومان</span>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">دسته بندی</h6>
                            <select name="category_id" class="form-control">
                                <option selected disabled>انتخاب دسته بندی...</option>
                                @foreach($categories as $category)
                                <option @selected(request()->query('category_id')==$category->id) value="{{$category->id}}">{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">مدل خودرو</h6>
                            <select name="car_id" class="form-control">
                                <option selected disabled>انتخاب خودرو...</option>
                                @foreach($cars as $car)
                                <option @selected(request()->query('car_id')==$car->id) value="{{$car->id}}">{{$car->model}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Discount Filter -->
                        <div class="mb-4">
                            <label for="discount">فروش ویژه</label>
                                <input type="checkbox" @checked(request()->query('discount')=='on') name="discount" id="discount" style="height: 16px; width:16px;">
                        </div>
                        <input type="submit" class="btn btn-primary btn-block" value="اعمال فیلترها">
                        <a href="{{ url()->current() }}" class="btn btn-outline-danger btn-block mt-3">
                            <i class="fa fa-times"></i> حذف همه فیلترها
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="col-lg-9">
            <div class="row">
                <!-- Product Cards -->
                @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    @if($product->percentage != null)
                    <x-discount-card id="{{$product->id}}" title="{{$product->title}}" image="{{$product->thumbnail_1}}" price="{{$product->price}}" percentage="{{$product->percentage}}" />
                    @else
                    <x-card id="{{$product->id}}" title="{{$product->title}}" image="{{$product->thumbnail_1}}" price="{{$product->price}}" />
                    @endif
                </div>
                @empty
                <p class="font-weight-bolder badge-warning rounded p-3 mx-auto mt-4">محصولی یافت نشد <i class="fa fa-exclamation-circle" aria-hidden="true"></i></p>
                @endforelse

            </div>

            <!-- Pagination -->
            {{$products->onEachSide(1)->links()}}
        </div>
    </div>
</div>

<script>
    // Price Range Display
    const lowerPriceInput = document.querySelector('#lowerPrice');
    const upperPriceInput = document.querySelector('#upperPrice');
    const lowerPriceValue = document.querySelector('#lowerPriceValue');
    const upperPriceValue = document.querySelector('#upperPriceValue');
    const priceRangeValue = document.querySelector('#priceRangeValue');

    function updatePriceDisplay() {
        const lowerValue = parseInt(lowerPriceInput.value).toLocaleString();
        const upperValue = parseInt(upperPriceInput.value).toLocaleString();

        // Update the individual displays
        lowerPriceValue.textContent = `${lowerValue} تومان`;
        upperPriceValue.textContent = `${upperValue} تومان`;

        // Update the combined display
        priceRangeValue.textContent = `${lowerValue} تا ${upperValue} تومان`;

        // Ensure the lower price doesn't exceed the upper price and vice versa
        if (parseInt(lowerPriceInput.value) > parseInt(upperPriceInput.value)) {
            lowerPriceInput.value = upperPriceInput.value;
        }
        if (parseInt(upperPriceInput.value) < parseInt(lowerPriceInput.value)) {
            upperPriceInput.value = lowerPriceInput.value;
        }
    }

    lowerPriceInput.addEventListener('input', updatePriceDisplay);
    upperPriceInput.addEventListener('input', updatePriceDisplay);

    // Initialize the display
    updatePriceDisplay();

    // Filter Toggle
    document.querySelectorAll('.custom-control-input').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            // Add filter logic here
            console.log('Filter changed');
        });
    });
</script>

@endsection