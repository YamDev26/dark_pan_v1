@extends('app')
@section('title', 'Statistik Detail')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Statistique</h4>
          <h4 class="mb-0">Trimestre 1</h4>
          <div class="d-flex">
            <div class="mx-2">
              <select id="mySelect" class="form-select form-select w-auto border-0 text-color-3">
                <option value="">Search ...</option>
                <option value="">Generate pdf ...</option>
                <option value="">Format excel ...</option>
              </select>
            </div>
            <a href="{{ route('resultat.index') }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-2">
            <div class="bg-secondary rounded h-100">
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-cycle1-tab" data-bs-toggle="tab" data-bs-target="#nav-cycle1" type="button" role="tab" aria-controls="nav-cycle1"
                      aria-selected="true">Cycle 1</button>
                    <button class="nav-link" id="nav-cycle2-tab" data-bs-toggle="tab" data-bs-target="#nav-cycle2" type="button" role="tab"
                      aria-controls="nav-cycle2" aria-selected="false">Cycle 2</button>
                    <button class="nav-link" id="nav-total-tab" data-bs-toggle="tab" data-bs-target="#nav-total" type="button" role="tab"
                      aria-controls="nav-total" aria-selected="false">Total</button>
                  </div>
                </nav>
                <div class="tab-content pt-3" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-cycle1" role="tabpanel" aria-labelledby="nav-cycle1-tab">
                    @include('partials._table_cycle_1_statistik')
                  </div>
                  <div class="tab-pane fade" id="nav-cycle2" role="tabpanel" aria-labelledby="nav-cycle2-tab">
                    @include('partials._table_cycle_2_statistik')
                  </div>
                  <div class="tab-pane fade" id="nav-total" role="tabpanel" aria-labelledby="nav-total-tab">
                    @include('partials._table_total_statistik')
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection