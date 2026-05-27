@extends('app')
@section('title', 'Register '.$level['symbol'])
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
      <h4 class="mb-0">Inscrit {{ $level['symbol'] }}</h4>
      <a href="{{ route('register.index') }}" class="btn btn-outline-light py-1 mx-2">Return</a>
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
<!-- Modal Detail -->
@include('partials._modal_register_detail')
<!-- Modal Delete -->
@include('partials._modal_register_delete')
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('register.yajra_2', $level['id']) }}',
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

    $(document).on('click', '.deleteBtn', function() {
      $id = $(this).data('id');
      ajax($id, function(data) {
        $('#dtleName').text(data['name']);
        $('#dtleGenre').text(data['genre'] == 'F' ? 'Feminin':'Masculin');
        $('#dtleMatricul').text(data['matricul']);
        $('#dtleClass').text(data['classe']);
        $('#idDelete').val($id);
        $('#DlteModal').modal('show');
      });
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

  })
</script>
@endsection