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
      <h4 class="mb-0">Edit moyenne</h4>
      <div class="my-0">
        <h4 class='my-0'>{{ 
          $classe['libelle'].' ~ '.$matter['matter']['symbol'] }}
        </h4>
        <span class="my-0">
          {{ ucwords($cutting['cutting']['libelle']) }}
        </span>
      </div>
      <Div class="d-flex">
        <button type="button" class="btn btn-outline-primary py-1 mx-3" data-bs-toggle="modal" data-bs-target="#AddModal">Import</button>
        <a href="{{ route('moyenne.list', ($classe->id.'_'.$matter->id.'_'.$cutting->id)) }}" class="btn btn-outline-light py-1">Return</a>
      </Div>
    </div>
    <form action="{{ route('moyenne.store',($classe->id.'_'.$matter->id.'_'.$cutting->id)) }}" method="post" id="myForm">
      @csrf
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="myTable">
          <thead>
            <tr class="text-white">
              <th scope="col"></th>
              <th scope="col">Matricule</th>
              <th scope="col">Nom</th>
              <th scope="col">Prénoms</th>
              <th scope="col">Genre</th>
              <th scope="col" class="text-center" style="width: 14%">Moyenne</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($datas as $i => $item)
              <tr>
                <td class="text-center">{{ $i < 9 ? '0'.($i+1):($i+1) }}</td>
                <td class="text-left">{{ $item->matricul }}</td>
                <td class="text-left">{{ strtoupper($item->first) }}</td>
                <td class="text-left">{{ ucwords($item->last) }}</td>
                <td class="text-left">{{ $item->genre == 'F' ? 'Feminin':'Masculin' }}</td>
                <td class="d-flex py-0 text-center">
                  <input type="hidden" name="students[]" value="{{ $item->id.'_'.$item->genre }}">
                  <input type="text" name="moyen1[]" class="form-control mx-0 input" value="{{ $item->moyenne }}" placeholder="---" style="width: 90px; background: none; font-size: 19px">
                  <span class="mt-2 px-1 d-flex" style="font-size: 19px"> / 20</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <hr style="border: 2px solid">
      <div class="text-center">
        <input type="hidden" name="string" value="{{ $classe->id.'_'.$matter->id.'_'.$cutting->id }}">
        <input type="hidden" name="frensh" value="oui">
        <button type="button" class="btn btn-primary w-25 py-2" data-bs-toggle="modal" data-bs-target="#myModal">Valider</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Confirm Validate -->
@include('partials._modal_validate')
<!-- Modal Import FIle -->
@include('partials._modal_import',[
  'url' => route('moyenne.import', ($classe->id.'_'.$matter->id.'_'.$cutting->id)),
  'export' => route('moyenne.export', ($classe->id.'_'.$matter->id.'_'.$cutting->id))
])
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    $('#myTable').on('keyup', '.input', function() {
      let value = this.value.replace(/[^0-9.]/g, '');
      const parts = value.split('.');
      if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
      }

      value = value.substring(0, 5);
      let max = 20;
      if (parseFloat(value) > max) {
        value = max;
      }
      this.value = value;
    });

    // Rendre tous les éléments visibles temporairement pour que les champs soient inclus dans le formulaire
    $(document).on('click', 'button[type="submit"]', function () {
      let table = $('#myTable').DataTable();
      table.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var row = this.node();
        if (!$(row).is(':visible')) {
          $(row).find('input, select, textarea').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            $('<input>').attr({
              type: 'hidden',
              name: name,
              value: value
            }).appendTo('#myForm');
          });
        }
      });
      $('#myForm').submit(); // Envoie du formulaire
    });


    $('#myTable').DataTable({
      processing: true,
      ordering: false,
    });
    
  })
</script>
@endsection