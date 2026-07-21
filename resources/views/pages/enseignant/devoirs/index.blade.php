@extends('app')
@section('title', 'Devoirs list')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class='my-0' id="str">Gestion des devoirs</h4>
          <div class="d-flex">
            <button id="addNew" class="btn btn-outline-primary py-1 mx-2">Nouveau</button>
          </div>
        </div>
        <hr class="mt-0">
        <div class="my-2">
          @php
            $hasActif = collect($data)->contains('actif', 2);
          @endphp
          <div class="bg-secondary rounded h-100">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              @foreach ($data as $i => $item)
                @php
                  $isActive = $item['actif'] == 2 || (!$hasActif && $loop->first);
                @endphp
                <button class="nav-link {{ $isActive ? 'active' : '' }}" id="{{ $isActive ? 'actif' : 'tab-'.$i  }}" data-atf="{{ $item['actif'] }}" data-id="{{ $item['id'] }}" data-bs-toggle="tab" data-bs-target="#content-{{ $i }}" type="button" role="tab" aria-controls="content-{{ $i }}" aria-selected="{{ $isActive ? 'true' : 'false' }}">
                  {{ ucwords($item['cutting']) }}
                </button>
              @endforeach
            </div>
            <div class="tab-content pt-1" id="nav-tabContent">
              @foreach ($data as $i => $item)
                @php
                  $isActive = $item['actif'] == 2 || (!$hasActif && $loop->first);
                @endphp
                <div class="tab-pane fade {{ $isActive ? 'active show' : '' }}" id="content-{{ $i }}" role="tabpanel" aria-labelledby="tab-{{ $i }}">
                  @include('partials._table_devoirs', [
                    'data' => $item['devoirs'],
                    'url' => 'evaluation.list'
                  ])
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Add Evaluated -->
@include('partials._modal_add_devoirs')


<!-- Modal Delete Evaluated -->
<div class="modal" id="dlteModal" tabindex="-1" aria-labelledby="dlteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header pt-2 pb-0 mb-0">
        <h5 class="modal-title" id="myModalLabel">Delete Devoir</h5>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body pt-4">
        <div class="text-center">
          <p style="font-size: 23px">Suppression</p>
          <div class="my-2">
            <h5 id="title"></h5>
            <p id="libelle"></p>
          </div>
          <p>Cliquez sur 'Valider' pour continuer.</p>
          <i class="fa fa-trash" style="font-size: 30px"></i>
        </div>
      </div>
      <div class="modal-footer mb-0">
        <form action="#" method="get" id="formDlte">
          @csrf
          <input type="hidden" name="id" id="inputDlte">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
        </form>
        </div>
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


    $('#addNew').on('click', function() {
      $("#myModal").modal("show");
    });

    $(document).on('change', '#getClasse', function() {
      let id = $(this).val();
      if(id) {
        $.ajax({
          url: "{{ route('devoirs.create', ':id') }}".replace(':id', id),
          type: 'get',
          success: function (response) {
            let html = response.map((item, index) => `
              <div class="form-check form-check-inline mx-1">
                <input
                  type="radio"
                  name="matter"
                  class="form-check-input"
                  id="mat-${item.id}"
                  value="${item.id}"
                  ${index === 0 ? 'checked' : ''}
                >
                <label class="form-check-label" for="mat-${item.id}">
                  ${item.symbol}
                </label>
              </div>
            `).join('');
            $('#listMatter').html(html);
          },
        });
      }
      else {
        $('#listMatter').text('--- --- ---');
      }
    });


    $(document).on('click', '.delete', function() {
      let id = $(this).data('id');

      if(id) {
        $.ajax({
          url: "{{ route('devoirs.edit', ':id') }}".replace(':id', id),
          type: 'get',
          success: function (response) {
            console.log(response);
            $('#title').text(
              response['classe'] +' ~ '+
              response['devoir']
            );
            $('#libelle').text(
              'Le '+response['date']+
              ' à '+response['debut']
            );
            $("#dlteModal").modal("show");
          },
        });
        $('#formDlte').attr(
          'action',
          "{{ route('devoirs.dtele', ':id') }}".replace(':id', id)
        );
      }
    });


    function getDevoirs(id) {
      $.ajax({
        url: "{{ route('devoirs.edit', ':id') }}".replace(':id', id),
        type: 'get',
        success: function (response) {
          return response;
        },
      });
    }


    function updateSelected(element) {
      const str = $('#str').data('str');
      const atf = $(element).data('atf');
      const id = $(element).data('id');

      $('#addNew').prop('disabled', (atf <= 2 ? false:true));
      $('#cutting').val(atf <= 2 ? id : '');
      $('#addNew').attr('data-id', str+'_'+id);
    }

  })
</script>
@endsection