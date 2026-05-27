@extends('app')
@section('title', 'Cutting')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary text-center rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4" style="border-bottom: 1px solid rgb(46, 46, 46)">
      <h6 class="mb-0">Découpage {{ $year->libelle }}</h6>
      <button type="button" class="btn text-primary" data-bs-toggle="modal" data-bs-target="#{{ $edit ? 'editModal':'myModal' }}">
        {{ $edit ? 'Edit':'Add' }}
      </button>
    </div>
    <!-- Livewire  -->
    @livewire('super-admin.cutting',['year' => $year])
  </div>
</div>
@endsection