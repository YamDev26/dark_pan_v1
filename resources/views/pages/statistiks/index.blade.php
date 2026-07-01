@extends('app')
@section('title', 'Statistik Detail')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Statistique</h4>
          <h4 class="mb-0">{{ ucwords($cutting->cutting->libelle) }}</h4>
          <div class="d-flex">
            <div class="mx-2">
              <select id="mySelect" class="form-select form-select w-auto border-0 text-color-3">
                <option value="">Search ...</option>
                <option value="pdf">Generate pdf</option>
                <option value="excel">Format excel</option>
                @if ($close)
                  <option value="modal">Boucler tout</option>
                @endif
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
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('statistik.store', $cutting->id) }}" method="get">
        @csrf
        <div class="modal-header pt-2 pb-0 mb-0">
          <h5 class="modal-title" id="myModalLabel">Cloture {{ $cutting->cutting->libelle }}</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body">
          <div class="text-center my-0">
            <p class="m-0" style="font-size: 50px">
              <i class="fa fa-exclamation-triangle text-warning"></i>
            </p>
            Attention <br> 
            Après cette action, aucune modification ni enregistrement ne pourra être effectué pour ce 
            {{ $cutting->cutting->libelle }}.

            <p>Cliquez sur 'Valider' pour continuer.</p>
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
@section('script')
<script>
  $(document).ready(function() {

    $('#mySelect').on('change', function() {
      switch (this.value) {
        case 'modal':
          $("#myModal").modal("show");
          break;
        case 'url':
          let id = $('#selected option:selected').data('id');
          window.location.href = "{{ route('note.index', ':id') }}"
          .replace(':id', id);
          break;
      }

      this.selectedIndex = 0;
    });

  })
</script>
@endsection