@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Wishlist</h2>
      
      @if ($productItems->count() > 0)
      <div class="shopping-cart">
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($productItems as $productItem)
                    <tr>
                        <td>
                        <div class="shopping-cart__product-item">
                            <img loading="lazy" src="{{ asset('uploads/products/thumbnails') }}/{{ $productItem->model->image }}" width="120" height="120" alt="{{ $productItem->name }}" />
                        </div>
                        </td>
                        <td>
                        <div class="shopping-cart__product-item__detail">
                            <h4>{{ $productItem->name }}</h4>
                            <ul class="shopping-cart__product-item__options">
                            <li>Color: Yellow</li>
                            <li>Size: L</li>
                            </ul>
                        </div>
                        </td>
                        <td>
                        <span class="shopping-cart__product-price">${{ $productItem->price }}</span>
                        </td>
                        <td>
                        <div class="qty-control position-relative">
                            {{ $productItem->qty }}                            
                        </div><!-- .qty-control -->
                        </td>
                        <td>
                            <div class="d-flex">
                                <div class="me-2">
                                    <form action="{{ route('wishlist.move.to.cart', ['rowId'=>$productItem->rowId]) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Move to Cart</button>
                                    </form>
                                </div>
                                <div class="">
                                    <form action="{{ route('wishlist.remove', ['rowId'=>$productItem->rowId]) }}" method="post" id="remove-item-{{ $productItem->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Remove From Wishlist</button>
                                    </form>
                                </div>
                            </div>
                            
                            
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="cart-table-footer">
            <form action="#" class="position-relative bg-body">
              <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code">
              <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                value="APPLY COUPON">
            </form>
            <form action="{{ route('wishlist.clear') }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-light">CLEAR WISHLIST</button>
            </form>
          </div>
        </div>
      </div>
      @else
        <div class="text-center mt-5">
          <h2>Your wishlist is empty.</h2>
          <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
      @endif
      
    </section>
  </main>
@endsection