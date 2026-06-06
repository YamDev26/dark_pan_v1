@extends('app')
@section('title', 'School Year')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary text-center rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4" style="border-bottom: 1px solid rgb(46, 46, 46)">
      <h4 class="mb-0">List School Year</h4>
      <button type="button" class="btn btn-outline-primary py-1 mb-2" data-bs-toggle="modal" data-bs-target="#myModal">Add New</button>
    </div>
    <!-- Livewire  -->
    @livewire('super-admin.year')
  </div>
</div>
<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('school_year.create') }}" method="post">
        @csrf
        <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">New School Year</h5>
        </div>
        <div class="modal-body py-4">
          <div class="mb-3">
            <label for="libelle" class="form-label">Année Scolaire<span class="text-primary">*</span> :</label>
            <input type="text" name="year" class="form-control" id="libelle" placeholder="Nouvelle Année Scolaire">
          </div>
          <div class="mb-0">
            <label class="form-label">Découpage Scolaire <span class="text-primary">*</span> :</label><br>
            <div class="d-flex align-items-center justify-content-between px-2">
              <div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="radio" class="form-check-input" id="inlineRadio1" value="3" checked>
                  <label class="form-check-label" for="inlineRadio1">Trimmestre</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="radio" class="form-check-input" id="inlineRadio2" value="2">
                  <label class="form-check-label" for="inlineRadio2">Semestre</label>
                </div>
              </div>
              <div class="form-check">
                <input type="checkbox" name="checked" class="form-check-input" id="gridCheck1" checked>
                <label class="form-check-label" for="gridCheck1">Activé</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer mb-0">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection