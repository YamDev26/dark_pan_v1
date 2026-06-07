@extends('app')
@section('title', 'Moyenne')
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
      <h4 class="mb-0">Liste moyenne</h4>
      <div class="my-0">
        <h4 class='my-0'>{{ 
          $classe['libelle'].' ~ '.$matter['matter']['symbol'] }}
        </h4>
        <span class="my-0">
          {{ ucwords($cutting['cutting']['libelle']) }}
        </span>
      </div>
      <Div class="d-flex">
        <a href="{{ route('moyenne.create', ($classe->id.'_'.$matter->id.'_'.$cutting->id)) }}" class="btn btn-outline-primary py-1 mx-3">Edit</a>
        <a href="{{ route('moyenne.show', ($classe->id.'_'.$cutting->id)) }}" class="btn btn-outline-light py-1">Return</a>
      </Div>
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
            <th scope="col">CF</th>
            <th scope="col">OG</th>
            <th scope="col">EO</th>
            <th scope="col">Moyenne</th>
            <th scope="col">Rang</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('moyenne.frensh', ($classe->id.'_'.$matter->id.'_'.$cutting->id)) }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'matricule', className: 'text-left'},
        {data: 'first', className: 'text-left'},
        {data: 'last', className: 'text-left'},
        {data: 'genre', className: 'text-left'},
        {data: 'cf', className: 'text-center'},
        {data: 'og', className: 'text-center'},
        {data: 'eo', className: 'text-center'},
        {data: 'moyenne', className: 'text-center'},
        {data: 'rang', className: 'text-center'},
      ],
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });
  })
</script>
@endsection