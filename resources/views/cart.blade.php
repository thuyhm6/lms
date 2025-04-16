@extends('layouts.app')
@section('content')
<style>
  .text-success {
       color: #28a745;

  }
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Cart</h2>
      <div class="checkout-steps">
        <a href="javascript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Shipping and Checkout</span>
            <em>Checkout Your Items List</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Review And Submit Your Order</em>
          </span>
        </a>
      </div>
      <div class="shopping-cart">
        @if ($cartItems->count() > 0)
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $cartItem)
                <tr class="shopping-cart-item">
                    <td>
                    <div class="shopping-cart__product-item">
                        <img loading="lazy" src="{{ asset('uploads/products/thumbnails') }}/{{ $cartItem->model->image }}" width="120" height="120" alt="{{ $cartItem->name }}" />
                    </div>
                    </td>
                    <td>
                    <div class="shopping-cart__product-item__detail">
                        <h4>{{ $cartItem->name }}</h4>
                        <ul class="shopping-cart__product-item__options">
                        <li>Color: Yellow</li>
                        <li>Size: L</li>
                        </ul>
                    </div>
                    </td>
                    <td>
                    <span class="shopping-cart__product-price">${{ $cartItem->price }}</span>
                    </td>
                    <td>
                    <div class="qty-control position-relative">
                        <input type="number" name="quantity" value="{{ $cartItem->qty }}" min="1" class="qty-control__number text-center" data-row-id="{{ $cartItem->rowId }}">
                        <form action="{{ route('cart.reduce.qty', ['rowId'=>$cartItem->rowId]) }}" method="post" class="reduce-form">
                          @csrf
                          @method('PUT')
                          <div class="qty-control__reduce">-</div>
                        </form>
                        <form action="{{ route('cart.increase.qty', ['rowId'=>$cartItem->rowId]) }}" method="post" class="increase-form">
                          @csrf
                          @method('PUT')
                          <div class="qty-control__increase">+</div>
                        </form>
                    </div>
                    </td>
                    <td>
                    <span class="shopping-cart__subtotal" data-subtotal="{{ $cartItem->subtotal() }}">${{ $cartItem->subtotal() }}</span>
                    </td>
                    <td>
                      <a href="javascript:void(0)" class="remove-cart" data-id="{{ $cartItem->rowId }}">Xóa</a>
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="cart-table-footer">
            @if (!Session::has('coupon'))
              <form action="{{ route('cart.coupon.apply') }}" class="position-relative bg-body" method="POST">
                @csrf
                <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code" value="">
                <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit" value="APPLY COUPON">
              </form>
            @else
              <form action="{{ route('cart.coupon.remove') }}" class="position-relative bg-body" method="POST">
                @csrf
                @method('DELETE')
                <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code" value="@if (Session::has('coupon')) {{ Session::get('coupon')['code'] }} Applied! @endif">
                <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit" value="REMOVE COUPON">
              </form>
            @endif
            

            <form action="{{ route('cart.clear',['rowId'=>$cartItem->rowId]) }}" class="position-relative bg-body" method="POST">
              @csrf
              @method('DELETE')
              <button class="btn btn-light" type="submit">CLEAR CART</button>
            </form>
          </div>
          <div>
            @if (Session::has('success'))
              <p class="text-success">{{ Session::get('success') }}</p>
            @elseif (Session::has('error'))
              <p class="text-error">{{ Session::get('error') }}</p>
            @endif
          </div>
        </div>
        <div class="shopping-cart__totals-wrapper">
          <div class="sticky-content">
            <div class="shopping-cart__totals">
              <h3>Cart Totals</h3>
              @if (Session::has('discounts'))
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td>${{ Cart::instance('cart')->subtotal() }}</td>
                  </tr>
                  <tr>
                    <th>Discount {{ Session::get('coupon')['code'] }}</th>
                    <td>${{ Session::get('discounts')['discount'] }}</td>
                  </tr>
                  <tr>
                    <th>Subtotal After Discount</th>
                    <td>${{ Session::get('discounts')['subtotal'] }}</td>
                  </tr>
                  <tr>
                    <th>Shipping</th>
                    <td>Free</td>
                  </tr>
                  <tr>
                    <th>VAT</th>
                    <td>${{ Session::get('discounts')['tax'] }}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>${{ Session::get('discounts')['total'] }}</td>
                  </tr>
                </tbody>
              </table>
              @else  
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td>${{ Cart::instance('cart')->subtotal() }}</td>
                  </tr>
                  <tr>
                    <th>Shipping</th>
                    <td>Free</td>
                  </tr>
                  <tr>
                    <th>VAT</th>
                    <td>${{ Cart::instance('cart')->tax() }}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>${{ Cart::instance('cart')->total() }}</td>
                  </tr>
                </tbody>
              </table>
              @endif
            </div>
            <div class="mobile_fixed-btn_wrapper">
              <div class="button-wrapper container">
                <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
              </div>
            </div>
          </div>
        </div>
        @else
            <div class="ropw">
                <div class="col-md-12 text-center pt-5 bp-5">
                    <h1>Your shopping cart is empty.</h1>
                    <p>You have no items in your shopping cart. To add some, please visit our <a href="{{ route('shop.index') }}">Products</a> page.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        @endif
      </div>
    </section>
  </main>
@endsection

@push('scripts')
<script>
  $(function() {
    // Hàm cập nhật tổng cộng của giỏ hàng
    function updateCartTotals() {
      let total = 0;
      $('.shopping-cart__subtotal').each(function() {
        total += parseFloat(($(this).data('subtotal')).replace(/,/g, ''));
      });
      let vat = (total * {{ config('cart.tax') }})/100;

      $('.cart-totals tr:contains("VAT") td').text('$' + vat.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.cart-totals tr:contains("Subtotal") td').text('$' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.cart-totals tr:contains("Total") td').text('$' + (total + vat).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
    }
  
    // Xử lý khi nhấn nút giảm (-)
    $('.qty-control__reduce').on('click', function(e) {
      e.preventDefault();
      let $input = $(this).closest('.qty-control').find('input');
      let rowId = $input.data('row-id');
      let qty = parseInt($input.val());
  
      if (qty < 1) return; // Không cho phép số lượng nhỏ hơn 1
  
      updateQuantity(rowId, qty, $input);
    });
  
    // Xử lý khi nhấn nút tăng (+)
    $('.qty-control__increase').on('click', function(e) {
      e.preventDefault();
      let $input = $(this).closest('.qty-control').find('input');
      let rowId = $input.data('row-id');

      let qty = parseInt($input.val()) ;
      updateQuantity(rowId, qty, $input);
    });
  
    // Xử lý khi nhập số trực tiếp
    $('.qty-control__number').on('change', function() {
      let rowId = $(this).data('row-id');
      let qty = parseInt($(this).val());
  
      if (qty < 1 || isNaN(qty)) {
        $(this).val(1); // Đặt lại về 1 nếu giá trị không hợp lệ
        qty = 1;
      }
  
      updateQuantity(rowId, qty, $(this));
    });
  
    // Hàm gửi AJAX để cập nhật số lượng
    function updateQuantity(rowId, qty, $input) {
      $.ajax({
        url: '{{ url("cart/update-qty") }}/' + rowId, // Tạo route mới cho cập nhật số lượng
        method: 'PUT',
        data: {
          _token: '{{ csrf_token() }}',
          quantity: qty
        },
        success: function(response) {
          if (response.success) {
            // Cập nhật số lượng trên giao diện
            $input.val(qty);
            // Cập nhật subtotal cho sản phẩm
          let newSubtotal = (response.subtotal); // Đảm bảo là số
          let $subtotalCell = $input.closest('tr').find('.shopping-cart__subtotal');

          if ($subtotalCell.length > 0) { // Kiểm tra xem phần tử có được tìm thấy không
            $subtotalCell.text('$' + newSubtotal);
            $subtotalCell.data('subtotal', newSubtotal);
            console.log('Subtotal updated to:', newSubtotal); // Kiểm tra giá trị mới
          } else {
            console.error('Subtotal cell not found!');
          }
  
            // Cập nhật tổng cộng của giỏ hàng
            updateCartTotals();
          }
        },
        error: function(xhr) {
          console.log('Error:', xhr);
          alert('Có lỗi xảy ra, vui lòng thử lại.');
        }
      });
    }
  
    // Xử lý nút xóa sản phẩm
    $('.remove-cart').click(function() {
        let cartItemId = $(this).data('id'); // Lấy ID địa chỉ
        let button = $(this); // Lưu button để thao tác sau

        if(confirm('Bạn có chắc chắn muốn xóa đơn này không?')) {
            $.ajax({
                url: `/cart/remove/${cartItemId}`, // Route xóa
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}" // CSRF Token của Laravel
                },
                success: function(response) {
                    if(response.success) {
                        button.closest('.shopping-cart-item').fadeOut(400, function() {
                          $(this).remove(); // Xóa kh��i DOM
                            updateCartTotals();
                        }); // Xóa khỏi giao diện
                    } else {
                        alert("Không thể xóa đơn!");
                    }
                },
                error: function() {
                    alert("Đã xảy ra lỗi, vui lòng thử lại!");
                }
            });
        }
    });
  });
  </script>
  
@endpush