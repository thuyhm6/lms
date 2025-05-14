@extends('layouts.app') {{-- //Cần check chỗ này --}}
@section('title', 'Liên hệ')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="contact-us container">
      <div class="mw-930">
        <h2 class="page-title">CONTACT US</h2>
      </div>
    </section>

    <hr class="mt-2 text-secondary " />
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
      <div class="mw-930">
        <div class="contact-us__form">
            @if (Session::has('success'))
            <p class="alert alert-success">{{ Session::get('success') }}</p>
            @endif
          <form name="contact-us-form" class="needs-validation" action="{{ route('home.contact.send') }}" novalidate="" method="POST">
            @csrf
            <h3 class="mb-5">Get In Touch</h3>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="name" placeholder="Name *" required="" value="{{ old('name') }}">
              <label for="contact_us_name">Name *</label>
              @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="phone" placeholder="Phone *" required="" value="{{ old('phone') }}">
              <label for="contact_us_name">Phone *</label>
              @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-floating my-4">
              <input type="email" class="form-control" name="email" placeholder="Email address *" required="" value="{{ old('email') }}">
              <label for="contact_us_name">Email address *</label>
              @error('email')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="my-4">
              <textarea class="form-control form-control_gray" name="comment" placeholder="Your Message" cols="30" rows="8" required="" >{{ old('comment') }}</textarea>
              @error('comment')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="my-4">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>
@endsection