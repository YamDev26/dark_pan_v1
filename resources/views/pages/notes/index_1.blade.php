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
      <h4 class="mb-0">Moyenne {{ ucwords($cutting['cutting']['libelle']) }}</h4>
      <div class="my-0">
        <h4 class='my-0'>
          {{ $classe['libelle'].' ~ '.$matter['matter']['symbol'] }}
        </h4>
      </div>
      <div class="d-flex">
        <div class="mx-3">
          <select class="form-select form-select w-auto border-0 text-color-3" onchange="window.location.href=this.value;">
            <option value="">Choise ...</option>
          </select>
        </div>
        <a href="{{ route('evaluated.show', ($classe['id'].'_'.$matter['id'])) }}" class="btn btn-outline-light ml-2 py-1">Return</a>
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
            @foreach($evaluateds as $i => $item)
              <th scope="col" title="{{ 'Note '.$i+1 }}">{{ 'N_'.$i+1 }}</th>
            @endforeach
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

    let columns = [
      {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
      { data: 'matricul', className: 'text-left' },
      { data: 'first', className: 'text-left' },
      { data: 'last', className: 'text-left' },
      { data: 'genre', className: 'text-left' },
    ];

    @foreach($evaluateds as $i => $item)
      columns.push({
        data: 'N_{{ $i+1 }}',
        className: 'text-center',
        defaultContent: '--'
      });
    @endforeach

    columns.push(
      { data: 'moyenne', className: 'text-center' },
      { data: 'rang', className: 'text-center' },
    );

    $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('note.matter', ($classe['id'].'_'.$matter['id'].'_'.$cutting['id'])) }}',
      columns: columns,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });
  })
</script>
@endsection