@extends('app')
@section('title', 'Nationality')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary text-center rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4" style="border-bottom: 1px solid rgb(46, 46, 46)">
      <h6 class="mb-0">Liste des nationalités</h6>
    </div>
    <!-- Livewire  -->
    @livewire('super-admin.nationality')
  </div>
</div>
@endsection