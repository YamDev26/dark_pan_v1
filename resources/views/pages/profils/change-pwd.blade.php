@extends('app')
@section('title','Change PassWord')
@section('link')
<style>
 
</style>
@endsection
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row">
    <div class="col-sm-12 col-lg-8 offset-lg-2">
      <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2" style="border-bottom: 1px solid #6C7293">
          <h4 class="mb-0">Changé de mot de passe</h4>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="text-left">
          <form action="{{ route('profils.store') }}" method="post">
            @csrf
            <div class="mb-3">
              <label for="current_password" class="form-label">Mot de passe actuel<span class="text-danger">*</span> :</label>
              <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" placeholder="Mot de passe acteul">
              @error('current_password')
                <span class="form-text text-danger" role="alert">
                  {{$message}}
                </span>
              @enderror
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Nouveau mot de passe<span class="text-danger">*</span> :</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Nouveau mot de passe">
              @error('password')
                <span class="form-text text-danger" role="alert">
                  {{$message}}
                </span>
              @enderror
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirmer le mot de passe<span class="text-danger">*</span> :</label>
              <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Confirmer le mot de passe">
              @error('password_confirmation')
                <span class="form-text text-danger" role="alert">
                  {{$message}}
                </span>
              @enderror
            </div>
            <hr style="border: 1px solid">
            <div class="text-center">
              <button type="submit" class="btn btn-primary w-25 py-1">Valider From</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {

  });
</script>
@endsection