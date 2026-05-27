@extends('auths.app')
@section('title', 'Forgot pwd')
@section('content')
<div class="container-fluid">
  <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
      <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <a href="{{ route('password.request') }}" class="">
            <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>{{ config('app.name') }}</h3>
          </a>
          <h3>Forgot</h3>
        </div>
        <p class="text-white mb-4" style="font-size: 14px">Enter your registered email ID to reset the password</p>
        <form action="{{ route('password.email') }}" method="post">
          @csrf
          <div class="form-floating mb-3">
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com">
            <label for="email">Email Address</label>
          </div>
          <button type="submit" class="btn btn-primary py-2 w-100 my-4">Send</button>
          <p class="text-center mb-0">
            <a href="{{ route('login') }}">Back to Login</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection