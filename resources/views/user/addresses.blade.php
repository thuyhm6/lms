@extends('layouts.app')
@section('title','Thông tin địa chỉ')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Addresses</h2>
      <div class="row">
        <div class="col-lg-3">
            @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__address">
            <div class="row">
              <div class="col-6">
                <p class="notice">The following addresses will be used on the checkout page by default.</p>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                @endif
              </div>
              <div class="col-6 text-right">
                <a href="{{ route('user.account.address.add') }}" class="btn btn-sm btn-info">Add New</a>
              </div>
            </div>
            <div class="my-account__address-list row">
              @foreach ($addresses as $address)
                <div class="my-account__address-item col-md-6">
                    <div class="my-account__address-item__title">
                    <h5>{{$address->name }} - {{$address->phone }}
                        @if ($address->isdefault == 1 )
                        <i class="fa fa-check-circle text-success">Mặc định</i>
                        @endif
                    </h5>
                    <div class="action">
                      <a href="{{ route('user.account.address.edit', ['id'=>$address->id]) }}" class="text-primary">Cập nhật</a>
                      @if ($address->isdefault != 1 )
                          <a href="javascript:void(0);" class="btn btn-link text-primary delete-address" data-id="{{ $address->id }}">Xóa</a>
                      @endif
                    </div>
                    </div>
                    <div class="my-account__address-item__detail">
                    <p>{{$address->locality }} - {{$address->address }}</p>
                    <p>{{$address->city }}, {{$address->state }} - {{$address->country }}</p>
                    <p>{{$address->zip }}</p>
                    </div>
                </div>
              @endforeach
              <hr>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('.delete-address').click(function() {
        let addressId = $(this).data('id'); // Lấy ID địa chỉ
        let button = $(this); // Lưu button để thao tác sau

        if(confirm('Bạn có chắc chắn muốn xóa địa chỉ này không?')) {
            $.ajax({
                url: `/account-address/delete/${addressId}`, // Route xóa
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}" // CSRF Token của Laravel
                },
                success: function(response) {
                    if(response.success) {
                        button.closest('.my-account__address-item').fadeOut(); // Xóa khỏi giao diện
                    } else {
                        alert("Không thể xóa địa chỉ!");
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