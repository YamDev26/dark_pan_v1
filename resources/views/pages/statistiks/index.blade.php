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
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                      data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                      aria-selected="true">Niveau</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                      data-bs-target="#nav-profile" type="button" role="tab"
                      aria-controls="nav-profile" aria-selected="false">Série</button>
                  </div>
                </nav>
                <div class="tab-content pt-3" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    @include('partials._table_level_statistik',[
                      'datas' => $datas,
                      'result' => $result,
                    ])
                  </div>
                  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    @include('partials._table_serie_statistik')
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