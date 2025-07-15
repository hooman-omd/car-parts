<div class="container my-4">
    <div class="row">
    <div class="col-12">
        <div class="custom-breadcrumb text-right">
            <a href="{{route('home')}}" class="breadcrumb-link">خانه</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">{{$slot}}</span>
        </div>
    </div>
</div>
</div>