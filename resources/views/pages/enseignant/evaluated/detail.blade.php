@extends('app')
@section('title', 'Evaluated '.$classe['libelle'])
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">{{ $classe['libelle'] }}</h4>
          <div class="my-0 text-center">
            <h4 class='my-0' id="str" data-str="{{ $classe['id'].'_'.$matter['id'] }}">
              {{ $matter['matter']['symbol'] }}
            </h4>
            <span class="my-0">Gestion des évaluations</span>
          </div>
          <div class="d-flex">
            <button id="addNew" class="btn btn-outline-primary py-1 mx-2" title="Nouvelle évaluation">Nouvelle</button>
            <a href="{{ route('evaluation.index') }}" class="btn btn-outline-light py-1">Return</a>
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
                  @include('partials._table_evaluated', [
                    'data' => $item['evaluated'],
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
@include('partials._modal_new_evaluated',[
  'url' => route('evaluation.store')
])

<!-- Modal Add Evaluated -->
@include('partials._modal_edit_evaluated')

<!-- Modal Delete Evaluated -->
<div class="modal" id="dlteModal" tabindex="-1" aria-labelledby="dlteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header pt-2 pb-0 mb-0">
        <h5 class="modal-title" id="myModalLabel">Delete</h5>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body py-4">
        <div class="text-center">
          <p style="font-size: 23px">Suppression</p>
          <h4 id="libs"></h4>
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


    $(document).on('click', '.edit', function() {
      const id = $(this).data('id');
      const str = $('#str').data('str');
      if(id) {
        $.ajax({
          url: '{{ route('evaluation.edit') }}',
          type: 'get',
          data: { id: id },
          success: function (response) {
            const type = response['data']['evaluated_type_id'];
            const note = response['data']['value']*20; 
            const sub = response['data']['sub_matter_id'];
            
            afficheData(response['data'], type, note, sub);

            $('.radioEdit').prop(
              'disabled', (response['exist']) ? true:false
            );

            $('#formEdit').prop(
              'action', 
              "{{ route('evaluation.update', ':id') }}".replace(':id', (id+'-'+str))
            )
            $("#editModal").modal("show");
          },
        });
      }
    });


    $(document).on('click', '.delete', function() {
      $id = $(this).data('id');
      $str = $('#str').data('str');
      $libelle = $(this).data('lib');
      if($id) {
        $('#inputDlte').val($id);
        $('#libs').text($libelle);
        $('#formDlte').prop(
          'action', "{{ route('evaluation.detele', ':id') }}".replace(':id', ($id+'-'+$str))
        )
        $("#dlteModal").modal("show");
      }
    });


    function updateSelected(element) {
      const str = $('#str').data('str');
      const atf = $(element).data('atf');
      const id = $(element).data('id');

      $('#addNew').prop('disabled', (atf <= 2 ? false:true));
      $('#cutting').val(atf <= 2 ? id : '');
      $('#addNew').attr('data-id', str+'_'+id);
    }

    function afficheData(response, type, note, sub) {
      $('#typeEdit').find(`option[value="${type}"]`).prop('selected', true);
      $(`input[name="note"][value="${note}"]`).prop('checked', true);
      sub ? $(`input[name="subE"][value="${sub}"]`).prop('checked', true):null;
      $('#dateEdit').val(response['created']);
      $('#status').prop('checked', (response['actif'] == 1 ? true:false));
      $('#libEdit').text((response['actif'] == 1 ? 'Actif':'Inactif'));
      $('#evaluated').val(response['id']);
    }

  })
</script>
@endsection