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

  .tdInput {
    width: 200px
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
        <a href="{{ route('moyenne.list', ($matter->id.'_'.$cutting->id.'_'.$classe->id)) }}" class="btn btn-outline-light py-1">Return</a>
      </Div>
    </div>
    <form action="{{ route('moyenne.store',($matter->id.'_'.$cutting->id.'_'.$classe->id)) }}" method="post" id="myForm">
      @csrf
      <div class="table-responsive">
        <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
          <thead>
            <tr class="text-white">
              <th scope="col" class="text-center">N°</th>
              <th scope="col" class="text-center">Matricule</th>
              <th scope="col" class="text-center">Nom</th>
              <th scope="col" class="text-center">Prenoms</th>
              <th scope="col" class="text-center">Genre</th>
              <th scope="col" class="text-center">Moyenne</th>
            </tr>
          </thead>
          <tbody>
              
          </tbody>
        </table>
      </div>
      <hr style="border: 2px solid">
      <div class="text-center">
        <button type="button" class="btn btn-primary w-25 py-2" data-bs-toggle="modal" data-bs-target="#myModal">Valider</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Confirm Validate -->
@include('partials._modal_validate')
<!-- Modal Import FIle -->
@include('partials._modal_import',[
  'url' => route('moyenne.import', ($matter->id.'_'.$cutting->id.'_'.$classe->id)),
  'export' => route('moyenne.export', ($matter->id.'_'.$cutting->id.'_'.$classe->id))
])
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    $(document).on('input', 'input[name="moyen[]"]', function () {
      let value = this.value;
      value = value.replace(/[^0-9.,]/g, '');
      value = value.replace(',', '.');
      const parts = value.split('.');
      if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
      }
      if (parts[1]) {
        parts[1] = parts[1].substring(0, 2);
        value = parts[0] + '.' + parts[1];
      }
      value = value.substring(0, 5);
      const number = parseFloat(value);
      if (!isNaN(number) && number > 20) {
        value = '20';
      }
      this.value = value;
    });


    const table = $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('moyenne.yajra_3', ($matter->id.'_'.$cutting->id.'_'.$classe->id)) }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'matricule', className: 'text-left'},
        {data: 'first', className: 'text-left'},
        {data: 'last', className: 'text-left'},
        {data: 'genre', className: 'text-center'},
        {data: 'input', className: 'text-center tdInput', orderable: false, searchable: false},
      ],
      // responsive: true,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });

    $(document).on('click', 'button[type="submit"]', function() {
      table.$('input').each(function () {
        if (!$.contains(document, this)) {
          $(this).appendTo('#myForm');
        }
      });
      $('#myForm').submit(); // Envoie du formulaire
    });
    
  })
</script>
@endsection