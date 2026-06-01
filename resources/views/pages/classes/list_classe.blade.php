@extends('app')
@section('title', 'List '.$classe['libelle'])
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
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Classe {{ $classe['libelle'] }}</h4>
          <div class="d-flex">
            <select class="form-select form-select w-auto border-0 text-color-3 mx-2" onchange="window.location.href=this.value;">
              <option value="">Search ...</option>
              <option value="#">Emploi du temps</option>
              <option value="#">Liste Enseignant</option>
              <option value="#">Danwload pfd</option>
              @if (!($classe['inscrit'] >= $classe['effectif']))
                <option value="{{ route('classe.export',$classe['id']) }}">Fiche Inscription</option>
              @endif
            </select>
            <a href="{{ route('classe.show',$classe['level_id']) }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-4">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle" id="myTable">
                <thead>
                  <tr class="text-white">
                    <th scope="col"></th>
                    <th scope="col">Matricule</th>
                    <th scope="col">Nom & Prénoms</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Date naissance</th>
                    <th scope="col">Affecté</th>
                    <th scope="col">Rédoublant</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
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
      ajax: '{{ route('classe.yajra',$classe['id']) }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'matricul', className: 'text-left'},
        {data: 'name', className: 'text-left'},
        {data: 'genre', className: 'text-left'},
        {data: 'naissance', className: 'text-left'},
        {data: 'affect', className: 'text-centre'},
        {data: 'redoublant', className: 'text-centre'},
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