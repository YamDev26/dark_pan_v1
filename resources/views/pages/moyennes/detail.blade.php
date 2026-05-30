@extends('app')
@section('title', 'Moyenne '.$classe['libelle'])
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
      <h4 class="mb-0">Moyennes {{ ucwords($cutting->cutting->libelle).' '.$classe['libelle'] }}</h4>
      <Div class="d-flex">
        <div class="mx-3">
          <select class="form-select form-select w-auto border-0 text-color-3" onchange="window.location.href=this.value;">
            <option value="">Search ...</option>
            @foreach ($matters as $item)
              <option value="{{ route('moyenne.list', ($item->id.'_'.$cutting->id.'_'.$classe->id)) }}">
                {{ ucwords($item->symbol) }}
              </option>
            @endforeach
          </select>
        </div>
        <a href="{{ route('moyenne.index') }}" class="btn btn-outline-light py-1">Return</a>
      </Div>
    </div>
    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
        <thead>
          <tr class="text-white">
            <th scope="col" class="text-center">N°</th>
            <th scope="col" class="text-center">Matricule</th>
            <th scope="col" class="text-center">Nom & Prenoms</th>
            {{-- @foreach ($matters as $item)
              <th scope="col" class="text-center">{{ ucwords($item->symbol) }}</th>
            @endforeach --}}
            <th scope="col" class="text-center">Moy</th>
            <th scope="col" class="text-center">Rang</th>
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
      ajax: '{{ route('moyenne.result', ($classe->id.'_'.$cutting->id)) }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'matricule', name: 's.matricul', className: 'text-left'},
        {data: 'name', name: 's.first', className: 'text-left'},
        {data: 'moyenne', name: 'mt.moyenne', className: 'text-center', orderable: false, searchable: false},
        {data: 'rang', name: 'mt.rang', className: 'text-center', orderable: false, searchable: false},
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