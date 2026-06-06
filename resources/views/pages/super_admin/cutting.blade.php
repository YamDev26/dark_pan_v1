@extends('app')
@section('title', 'Cutting')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary text-center rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4" style="border-bottom: 1px solid rgb(46, 46, 46)">
      <h4 class="mb-0">Découpage {{ $year->libelle }}</h4>
      <button type="button" class="btn btn-outline-primary py-1 mb-2" data-bs-toggle="modal" data-bs-target="#{{ $edit ? 'editModal':'myModal' }}">
        Config
      </button>
    </div>
    <!-- Livewire  -->
    @livewire('super-admin.cutting',['year' => $year])
  </div>
</div>
@endsection