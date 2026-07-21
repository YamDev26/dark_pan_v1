@extends('app')
@section('title', 'List Moyenne '.$classe['libelle'])
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
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2" style="border-bottom: 1px solid #6C7293">
      <h4 class="mb-0">{{ ucwords($cutting['cutting']['libelle']) }}</h4>
      <div class="my-0">
        <h4 class='my-0'>{{ $classe['libelle'] }}</h4>
        <span class="my-0">
          Moyenne
        </span>
      </div>
      <div class="d-flex">
        <div class="mx-0">
          <select class="form-select form-select w-auto border-0 text-color-3" onchange="window.location.href=this.value;">
            <option value="">Matter ...</option>
            @foreach ($matters as $item)
              <option value="{{ route('moyenne.list', ($classe->id.'_'.$item->id.'_'.$cutting->id)) }}">
                {{ ucwords($item->symbol) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="mx-2">
          <select id="mySelect" class="form-select form-select w-auto border-0 text-color-3">
            <option value="">Autres ...</option>
            <option value="{{ route('moyenne.pdf', ($classe->id.'_'.$cutting->id)) }}" data-option="pdf">Voir le pdf</option>
            @if (!$close)
            <option value="modal" data-option="modal">Importation</option>
            <option value="{{ route('moyenne.classe', ($classe->id.'_'.$cutting->id)) }}" data-option="url">Non classés</option>
            @endif
          </select>
        </div>
        <a href="{{ route('moyenne.index') }}" class="btn btn-outline-light py-1">Return</a>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
        <thead>
          <tr class="text-white">
            <th scope="col" class="text-center"></th>
            <th scope="col" class="text-center">Matricule</th>
            <th scope="col" class="text-center">Nom & Prenoms</th>
            <th scope="col" class="text-center">Genre</th>
            @foreach ($matieres as $item)
              <th scope="col" class="text-center">{{ ucwords($item['symbol']) }}</th>
            @endforeach
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
@include('partials._modal_import',[
  'url' => route('moyenne.imports', $classe->id.'_'.$cutting->id),
  'export' => route('moyenne.exports', $classe->id.'_'.$cutting->id)
])
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    let columns = [
      {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
      { data: 'matricul', className: 'text-left' },
      { data: 'name', className: 'text-left' },
      { data: 'genre', className: 'text-center' },
    ];

    @foreach($matieres as $item)
      columns.push({
        data: '{{ $item['symbol'] }}',
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
      ajax: '{{ route('moyenne.result', ($classe->id.'_'.$cutting->id)) }}',
      columns: columns,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });


    $('#mySelect').on('change', function () {
      const $selected = $(this).find(':selected');
      const url = $selected.val();
      const option = $selected.data('option');
      
      if (!url) {
        this.selectedIndex = 0;
        return;
      }

      switch (option) {
        case 'pdf':
          window.open(url, '_blank');
          break;
        case 'modal':
          $('#AddModal').modal('show');
          break;
        default:
          window.location.href = url;
          break;
      }

      this.selectedIndex = 0;
    });

  })
</script>
@endsection