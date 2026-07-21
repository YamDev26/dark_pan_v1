@extends('app')
@section('title', 'Add Not '.$evaluat['get_classe']['libelle'])
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
      <h4 class="mb-0">Ajout note</h4>
      <div class="my-0">
        <h4 class='my-0'>{{ 
          $evaluat['get_classe']['libelle'].' ~ '.$evaluat['level_matter']['matter']['symbol'].($evaluat['sub_matter_id'] ? ' - '.$evaluat['sub_matter']['symbol']:'') }}
        </h4>
        <span class="my-0">
          {{ ucwords($evaluat['evaluated_type']['libelle']). ' du '.date('d/m/Y', strtotime($evaluat['created'])) }}
        </span>
      </div>
      <div class="my-0">
        <button type="button" class="btn btn-outline-warning py-1 mx-2" data-bs-toggle="modal" data-bs-target="#AddModal">Import</button>
        <a href="{{ route('evaluation.list', $evaluat['id']) }}" class="btn btn-outline-light m-2 py-1">Return</a>
      </div>
    </div>
    <form action="{{ route('evaluation.addNot', $evaluat['id']) }}" method="post" id="myForm">
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
              <th scope="col" class="text-center" style="width: 15%">Note</th>
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
                  <input type="hidden" name="str[]" value="{{ $item->id }}">
                  <input type="text" name="note[]" class="form-control mx-0 input" data-not="{{ $evaluat['value'] * 20 }}" placeholder="---" style="width: 90px; background: none; font-size: 19px">
                  <span class="mt-2 px-1 d-flex" style="font-size: 19px">
                    {{ '/ '.$evaluat['value'] * 20 }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <hr style="border: 1px solid">
        <input type="hidden" name="evaluat" value="{{ $evaluat['id'] }}">
        <div class="text-center">
          <button type="button" class="btn btn-primary w-25 py-1" data-bs-toggle="modal" data-bs-target="#myModal">Valider</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Import File -->
@include('partials._modal_import',[
  'url' => route('evaluation.import', $evaluat['id']),
  'export' => route('evaluation.export', $evaluat['id'])
])
<!-- Modal Validate -->
@include('partials._modal_validate')
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
      let not = $(this).data('not');
      if (parseFloat(value) > not) {
        value = not;
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