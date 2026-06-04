@extends('app')
@section('title', 'Evaluated '.$classe['libelle'])
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Classe {{ $classe['libelle'] }}</h4>
          <div class="my-0 text-center">
            <h4 class='my-0'>{{ $matter['matter']['symbol'] }}</h4>
            <span class="my-0">Gestion des évaluations</span>
          </div>
          <div class="d-flex">
            <select id="selected" class="form-select form-select w-auto border-0 text-color-3 mx-2">
              <option value="">Choise ...</option>
              <option value="modal">Nouvelle</option>
              <option value="url">Moyenne</option>
              <option value="#">Get Pdf</option>
            </select>
            <a href="{{ route('evaluated.index') }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr class="mt-0">
        <div class="my-2">
          <div class="bg-secondary rounded h-100">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              @foreach ($data as $i => $item)
                <button class="nav-link {{ ($item['actif'] == 2) ? 'active':($loop->first ? 'active' : '') }}" id="{{ $item['actif'] == 2 ? 'actif':'tab-'.$i  }}" data-atf="{{ $item['actif'] }}" data-id="{{ $item['id'] }}" data-bs-toggle="tab" data-bs-target="#content-{{ $i }}" type="button" role="tab" aria-controls="content-{{ $i }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                  {{ ucwords($item['cutting']) }}
                </button>
              @endforeach
            </div>
            <div class="tab-content pt-1" id="nav-tabContent">
              @foreach ($data as $i => $item)
                <div class="tab-pane fade {{ ($item['actif'] == 2) ? 'show active':($loop->first ? 'show active' : '') }}" id="content-{{ $i }}" role="tabpanel" aria-labelledby="tab-{{ $i }}">
                  @include('partials._table_evaluated', ['data' => $item['evaluated']])
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Evaluated -->
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('evaluated.store') }}" method="post">
        @csrf
        <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">New Evaluated</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-4">
          <div class="mb-2">
            <input type="hidden" name="matter" value="{{ $matter['id'] }}">
            <input type="hidden" name="classe" value="{{ $classe['id'] }}">
            <input type="hidden" name="cutting" id="cutting">
            <label for="type" class="col-form-label">Type Evaluation<span class="text-danger">*</span> :</label>
            <select name="type" id="type" class="form-select mb-2">
              <option value="">Select ...</option>
              @foreach ($getType as $item)
                <option value="{{ $item['id'] }}">{{ ucwords($item['libelle']) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label for="value" class="col-form-label">Value Evaluation<span class="text-danger">*</span> :</label>
            <select name="value" id="value" class="form-select mb-2">
              <option value="">Select ...</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="40">40</option>
            </select>
          </div>
          <div class="mb-0">
            <label for="date" class="col-form-label">Date Evaluation<span class="text-danger">*</span> :</label>
            <input type="date" name="date" class="form-control" id="date">
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

    // Initialisation
    updateSelected('#actif');

    $('.nav-link').on('click', function(){
      updateSelected(this);
    });


    $('#selected').on('change', function() {
      switch (this.value) {
        case 'modal':
          $("#myModal").modal("show");
          break;
        case 'url':
          window.location.href='{{ route('evaluated.index') }}';
          break;
      }
    });
    

    $(document).on('click', '.edit', function() {
      $value = $(this).data('id');
      if($value) {
        $.ajax({
          url: '{{ route('evaluated.edit') }}',
          type: 'get',
          data: { id: $value },
          success: function (response) {
            console.log(response);
          },
        });
      }
    });


    function updateSelected(element) {
      const atf = $(element).data('atf');
      const id = $(element).data('id');

      $('#selected option[value="modal"]').toggle(atf <= 2);
      $('#cutting').val(atf <= 2 ? id : '');
    }

  })
</script>
@endsection