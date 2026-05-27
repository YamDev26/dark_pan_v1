@extends('app')
@section('title','List Register')
@section('link')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
  .dataTables_length label, .select-info{
    display: none;
  }

  table.dataTable.no-footer {
    border-bottom: 1px solid black;
  }

  table.dataTable {
    border-collapse: collapse;
  }

  .text-left {
    text-align: left !important;
  }

  .dataTables_filter {
    margin-bottom: 20px
  }
</style>
@endsection
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary text-center rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2" style="border-bottom: 3px solid #6C7293">
      <h4 class="mb-0">Inscription</h4>
      <div class="d-flex">
        <div class="mr-3">
          <select class="form-select form-select w-auto border-0 text-color-3" onchange="window.location.href=this.value;">
            <option value="">Search ...</option>
            @foreach ($levels as $item)
              <option value="{{ route('register.show', $item['id']) }}">{{ ucwords($item['symbol']) }}</option>
            @endforeach
          </select>
        </div>
        <button type="button" class="btn btn-outline-danger py-1 mx-2" data-bs-toggle="modal" data-bs-target="#AddModal">Nouvelle</button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle" id="myTable">
        <thead>
          <tr class="text-white">
            <th scope="col"></th>
            <th scope="col">Matricule</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénoms</th>
            <th scope="col">Genre</th>
            <th scope="col">Classe</th>
            <th scope="col" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Modal Search Student -->
@include('partials._modal_register_search')
<!-- Modal Add Register -->
@include('partials._modal_register_add',['levels' => $levels])
<!-- Modal Detail -->
@include('partials._modal_register_detail')
<!-- Modal Delete Register -->
@include('partials._modal_register_delete')
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    // Exactement 9 caractères (8 chiffres + une lettre)
    $(document).on('input', '#matricul', function() {
      let value = $(this).val();
      value = value.replace(/[^0-9a-zA-Z]/g, '');
      value = value.slice(0, 9);
      let numbers = value.slice(0, 8).replace(/\D/g, '');
      let letter = value.slice(8, 9).replace(/[^a-zA-Z]/g, '');
      $(this).val(numbers + letter);
    });

    $(document).on('click', '#subMit', function(e) {
      e.preventDefault();
      $mat = $('#matricul').val();
      if($mat) {
        $.ajax({
          url: '{{ route('register.create') }}',
          method: 'GET',
          data: { info: $mat },
          success: function(data){
            if(!data) {
              $('#uderMatricul').text($mat);
              $('#block1').hide(); $('#block2').show();
            }
            else{
              $('#block2').hide(); $('#block1').show();
              $('#addName').text(data['name']);
              $('#addGenre').text('Genre : '+(data['genre'] == 'F' ? 'Feminin':'Masculin'));
              $('#addNaiss').text('Né'+(data['genre'] == 'F' ? 'é le ':' le ')+data['date']+' à '+data['lieu']);
              $('#addMtcl').text('Matricule : '+data['matricul']);
            }

            $("#myModal").modal("show");
            $('#AddModal').modal('hide');
            $('#matricul').val('');
          }
        })
      }
    });


    $(document).on('change','select[name="level"]', function() {
      let option = $(this).find(':selected');
      $val = option.val(); $symbol = option.data('symbol'); alert($symbol);
      geClasse($val);
    });


    $(document).on('click', '.dtlBtn', function() {
      ajax($(this).data('id'), function(data) {
        $('#dtlName').text(data['name']);
        $('#dtlMatricul').text(data['matricul']);
        $('#dtlGenre').text('Genre : '+(data['genre'] == 'F' ? 'Feminin':'Masculin'));
        $('#dtlNaiss').text('Né'+(data['genre'] == 'F' ? 'e ':' ')+data['date']+' à '+data['lieu']);
        $('#dtlResident').text('Résidence : '+data['residence']);
        $('#dtlAffect').text('Affecté'+(data['genre'] == 'F' ? 'e : ':' : ')+data['affect']);
        $('#dtlRedoub').text('Redoubant'+(data['genre'] == 'F' ? 'e : ':' : ')+data['redoubant']);
        $('#dtlBourse').text('Boursi'+(data['genre'] == 'F' ? 'ère : ':'er : ')+data['boursier']);
        $('#dtlLevel').text('Niveau : '+data['level']);
        $('#dtlClasse').text('Classe : '+data['classe']);
        $('#dateIscte').text('Inscrit'+(data['genre'] == 'F' ? 'e le : ':' le : ')+data['inscrit']);
        $('#dtlSerie').text('Série : '+data['serie']);
        $('#dtlLv2').text('Lv2 : '+(data['lv2'] == 'all' ? 'Allemand':'Espagnol'));
        data['serie'] ? $('#dtlSerie').show():$('#dtlSerie').hide();
        data['lv2'] ? $('#dtlLv2').show():$('#dtlLv2').hide();
        $("#DtailModal").modal("show"); // Affichage du modal ...
      });
    });


    $(document).on('click', '.deleteBtn', function() {
      $id = $(this).data('id');
      ajax($id, function(data) {
        console.log(data);
        $('#dtleName').text(data['name']);
        $('#dtleGenre').text(data['genre'] == 'F' ? 'Feminin':'Masculin');
        $('#dtleMatricul').text(data['matricul']);
        $('#dtleClass').text(data['classe']);
        $('#idDelete').val($id);
        $('#DlteModal').modal('show');
      });
    });


    $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('register.yajra_1') }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'matricul', className: 'text-left'},
        {data: 'first', className: 'text-left'},
        {data: 'last', className: 'text-left'},
        {data: 'genre', className: 'text-left'},
        {data: 'classe', className: 'text-left'},
        {data: 'action', className: 'text-center', orderable: false, searchable: false},
      ],
      // responsive: true,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });


    // Function Get Jquery Create ---

    function geClasse($level, $serie = null, $lv2 = null) {
      $.ajax({
        url: '{{ route('register.classe') }}',
        method: 'GET',
        data: {
          level: $level,
          serie: $serie,
          lv2: $lv2
        },
        success: function(data){
          console.log(data);
        }
      })
    }

    function ajax(id, callback) {
      $.ajax({
        url: '{{ route('register.search') }}',
        method: 'GET',
        data: {id: id},
        success: function(data){
          callback(data);
        }
      })
    }
  });
</script>
@endsection