@extends('layouts.app')
@section('title','Sửa địa chỉ')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Address</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__address">
              <div class="row">
                  <div class="col-6">
                      <p class="notice">The following addresses will be used on the checkout page by default.</p>
                  </div>
                  <div class="col-6 text-right">
                      <a href="{{ route('user.account.addresses') }}" class="btn btn-sm btn-danger">Back</a>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-12">
                      <div class="card mb-5">
                          <div class="card-header">
                              <h5>Add New Address</h5>
                          </div>
                          <div class="card-body">
                              <form action="{{ route('user.account.address.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="name" value="{{ $address->name }}">
                                              <label for="name">Full Name *</label>
                                              @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="phone" value="{{ $address->phone }}">
                                              <label for="phone">Phone Number *</label>
                                              @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="zip" value="{{ $address->zip }}">
                                              <label for="zip">Pincode *</label>
                                              @error('zip') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>
                                      </div>                        
                                      <div class="col-md-4">
                                          <div class="form-floating mt-3 mb-3">
                                              <input type="text" class="form-control" name="state" value="{{ $address->state }}">
                                              <label for="state">State *</label>
                                              @error('state') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>                            
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="city" value="{{ $address->city }}">
                                              <label for="city">Town / City *</label>
                                              @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="address" value="{{ $address->address }}">
                                              <label for="address">House no, Building Name *</label>
                                              @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="locality" value="{{ $address->locality }}">
                                              <label for="locality">Road Name, Area, Colony *</label>
                                              @error('locality') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>
                                      </div>    
                                      <div class="col-md-12">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="landmark" value="{{ $address->landmark }}">
                                              <label for="landmark">Landmark *</label>
                                              @error('landmark') <span class="text-danger">{{ $message }}</span> @enderror
                                          </div>
                                      </div>  
                                      <div class="col-md-6">
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="{{ $address->isdefault }}" id="isdefault" name="isdefault" 
                                              @if ($address->isdefault == 1) checked  @endif>
                                              <label class="form-check-label" for="isdefault">
                                                  Make as Default address
                                              </label>
                                          </div>
                                      </div>  
                                      <div class="col-md-12 text-right">
                                          <button type="submit" class="btn btn-success">Submit</button>
                                      </div>                                     
                                  </div>
                              </form> 
                          </div>
                      </div>
                  </div>
              </div>
              <hr>                    
          </div>
      </div>
      </div>
    </section>
  </main>
@endsection