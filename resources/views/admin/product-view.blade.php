@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.products') }}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">View product</div>
                </li>
            </ul>
        </div>
        <!-- form-view-product -->
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="#">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $product->id }}">
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0" value="{{ $product->name }}" aria-required="true" disabled>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0" value="{{ $product->name }}" aria-required="true" disabled>
                </fieldset>

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="category_id" disabled>
                                <option>Choose category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? "selected" : "" }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="brand_id" disabled>
                                <option>Choose Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? "selected" : "" }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span lass="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0" aria-required="true" disabled>{{ $product->short_description}}</textarea>
                </fieldset>

                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true" disabled>{{ $product->description}}</textarea>
                </fieldset>
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        @if ($product->image)
                        <div class="item" id="imgpreview">
                            <img src="{{ asset('uploads/products') }}/{{ $product->image }}" class="effect8" alt="{{ $product->name }}">
                        </div>
                        @endif
                        
                    </div>
                </fieldset>

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        @if ($product->images)
                            @foreach (explode(',', $product->images) as $img)
                                <div class="item gitem">
                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{ trim($img) }}" alt="">
                                </div>
                            @endforeach
                        @endif
                       
                    </div>
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" tabindex="0" value="{{ $product->regular_price}}" aria-required="true" disabled>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" tabindex="0" value="{{ $product->sale_price}}" aria-required="true" disabled>
                    </fieldset>
                </div>


                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0" value="{{ $product->SKU}}" aria-required="true" disabled>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" tabindex="0" value="{{ $product->quantity}}" aria-required="true" disabled>
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status" disabled>
                                <option value="instock" {{ $product->stock_status == "instock" ? "selected" : "" }}>InStock</option>
                                <option value="outofstock" {{ $product->stock_status == "outofstock" ? "selected" : "" }}>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select class="" name="featured" disabled>
                                <option value="0" {{ $product->featured == "0" ? "selected" : "" }}>No</option>
                                <option value="1" {{ $product->featured == "1" ? "selected" : "" }}>Yes</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
            </div>
        </form>
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection
