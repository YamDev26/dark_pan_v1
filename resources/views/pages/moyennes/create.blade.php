@extends('app')
@section('title', 'Moyenne Matter '.$classe['libelle'])
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
      <h4 class="mb-0">Add {{ ucwords($cutting->cutting->libelle).' '.$classe['libelle'] }}</h4>
      <h4 class="mb-0">{{ ucwords($matter->matter->symbol) }}</h4>
      <Div class="d-flex">
        <button type="button" class="btn btn-outline-primary py-1 mx-3" data-bs-toggle="modal" data-bs-target="#AddModal">Import</button>
        <a href="{{ route('moyenne.show', ($classe->id.'_'.$cutting->id)) }}" class="btn btn-outline-light py-1">Return</a>
      </Div>
    </div>
    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered table-hover mb-0">
        <thead>
          <tr class="text-white">
            <th scope="col" class="text-center">N°</th>
            <th scope="col" class="text-center">Matricule</th>
            <th scope="col" class="text-center">Nom & Prenoms</th>
            <th scope="col" class="text-center">Moyenne</th>
            <th scope="col" class="text-center">Rang</th>
          </tr>
        </thead>
        <tbody>
            
        </tbody>
      </table>
    </div>
  </div>
</div>
@include('partials._modal_import',[
  'url' => route('moyenne.import', ($matter->id.'_'.$cutting->id.'_'.$classe->id)),
  'export' => route('moyenne.export', ($matter->id.'_'.$cutting->id.'_'.$classe->id))
])
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('moyenne.yajra_1') }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'libelle', className: 'text-left'},
        {data: 'effectif', className: 'text-left'},
        {data: 'prof', className: 'text-left'},
        {data: 'action', className: 'text-center dropdown', orderable: false, searchable: false},
      ],
      // responsive: true,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });
  })
</script>
@endsection