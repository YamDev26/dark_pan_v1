@extends('app')
@section('title', 'List Note')
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
      <h4 class="mb-0">Liste note</h4>
      <div class="my-0">
        <h4 class='my-0'>
          {{ $evaluat['get_classe']['libelle'].' ~ '.$evaluat['level_matter']['matter']['symbol'].($evaluat['sub_matter_id'] ? ' - '.$evaluat['sub_matter']['symbol']:'') }}
        </h4>
        <span class="my-0">
          {{ ucwords($evaluat['evaluated_type']['libelle']). ' du '.date('d/m/Y', strtotime($evaluat['created'])) }}
        </span>
      </div>
      <div class="d-flex">
        <div class="mx-3">
          <select class="form-select form-select w-auto border-0 text-color-3" onchange="window.location.href=this.value;" {{ $evaluat['actif'] != 1 ? 'disabled':'' }}>
            <option value="">Choise ...</option>
            @if (!$existe)
              <option value="{{ route('note.create', $evaluat['id']) }}">Add Note</option>
            @else
              <option value="{{ route('note.edit', $evaluat['id']) }}">Edit Note</option>
              <option value="#">Get Pdf</option>
            @endif
          </select>
        </div>
        <a href="{{ route('evaluated.show',($evaluat['get_classe_id'].'_'.$evaluat['level_matter_id'])) }}" class="btn btn-outline-light ml-2 py-1">Return</a>
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
            <th scope="col">Value</th>
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
      ajax: '{{ route('note.yajra', $evaluat['id']) }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'matricule', className: 'text-left'},
        {data: 'first', className: 'text-left'},
        {data: 'last', className: 'text-left'},
        {data: 'genre', className: 'text-left'},
        {data: 'note', className: 'text-center'},
      ],
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });
  })
</script>
@endsection