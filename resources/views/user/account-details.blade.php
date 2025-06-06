@extends('layouts.app')
@section('content')
<style>
    .my-account__edit-form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    .form-floating {
        position: relative;
    }

    .form-floating input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px 15px;
        font-size: 16px;
        width: 100%;
        transition: border-color 0.3s ease;
    }

    .form-floating input:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }




    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .text-danger {
        font-size: 14px;
        margin-top: 5px;
    }
</style>
   <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Thôn tin tài khoản</h2>
      <div class="row">
        <div class="col-lg-3">
            @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__edit">
            <div class="my-account__edit-form">
              <form name="account_edit_form" action="{{ route('user.account.details.update') }}" method="POST" class="needs-validation" novalidate="">
                @csrf
                @method('PUT')
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" placeholder="Full Name" name="full_name" value="{{ $user->full_name }}" required="">
                      <label for="full_name">Họ Tên</label>
                      @error('full_name') <span style="color: red">{{ $message }}</span> @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value="{{ $user->mobile }}" required="">
                      <label for="mobile">Số điện thoại</label>
                      @error('mobile') <span style="color: red">{{ $message }}</span> @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{ $user->email }}" required="">
                      <label for="account_email">Email</label>
                      @error('email') <span style="color: red">{{ $message }}</span> @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="my-3">
                      <button type="submit" class="btn btn-primary">Lưu Thông Tin</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
