@extends('auths.app')
@section('title', 'Login')
@section('content')
<div class="container-fluid">
  <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
      <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <a href="{{ route('login') }}" class="">
            <h3 class="text-primary mb-0"><i class="fa fa-user-edit me-2"></i>{{ config('app.name') }}</h3>
            {{-- <span class="my-0 text-white" style="font-size: 11px">Le cœur de l'administration scolaire</span> --}}
          </a>
          <h3>Sign In</h3>
        </div>
        <form action="{{ route('login') }}" method="post">
          @csrf
          <div class="form-floating mb-3">
            <input type="email" name="email" id="email" class="form-control"value="{{ old('email') }}" placeholder="name@example.com">
            <label for="email">Adresse email</label>
          </div>
          <div class="form-floating mb-4">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            <label for="password">Mot de passe</label>
          </div>
          <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
              <input type="checkbox" name="remember_me" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}">Forgot Password</a>
          </div>
          <button type="submit" class="btn btn-primary py-2 w-100 mb-4">Sign In</button>
          <p class="text-center mb-0">Nouvelle Génération de Gestion</p>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection