@extends('app')
@section('title', 'Detail '.$level['symbol'])
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Detail {{ $level['symbol'] }}</h4>
          <div>
            <a href="{{ route('level.create', $level['id']) }}" class="btn btn-outline-primary py-1 mx-2">Edit</a>
            <a href="{{ route('level.index') }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="bg-secondary rounded h-100">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="false">Home</button>
              <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</button>
              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
            </div>
          </nav>
          <div class="tab-content pt-3" id="nav-tabContent">
            <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
              @include('partials._table_level',['data' => $data])
            </div>
            <div class="tab-pane fade active show" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
              @include('partials._table_level',['data' => $data])
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
              @include('partials._table_level',['data' => $data])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection