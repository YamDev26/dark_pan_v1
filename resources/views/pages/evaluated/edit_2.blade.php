@extends('app')
@section('title', 'Edit Moyenne')
@section('link')<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

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
      <h4 class="mb-0">Edit moyenne</h4>
      <div class="my-0">
        <h4 class='my-0'>{{ 
          $classe['libelle'].' ~ '.$matter['matter']['symbol'] }}
        </h4>
        <span class="my-0">
          {{ ucwords($cutting['cutting']['libelle']) }}
        </span>
      </div>
      <div class="my-0">
        <a href="{{ route('note.index', $str) }}" class="btn btn-outline-light m-2 py-1">Return</a>
      </div>
    </div>
    <form action="{{ route('evaluated.moyenne_edit', $str) }}" method="post" id="myForm">
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
              <th scope="col" class="text-center" style="width: 15%">Moyenne CF</th>
              <th scope="col" class="text-center" style="width: 15%">Moyenne OG</th>
              <th scope="col" class="text-center" style="width: 15%" >Moyenne EO</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($datas as $i => $item)
              <tr>
                <input type="hidden" name="students[]" value="{{ $item->id.'_'.$item->genre }}">
                <td class="text-center">{{ $i < 9 ? '0'.($i+1):($i+1) }}</td>
                <td class="text-left">{{ $item->matricul }}</td>
                <td class="text-left">{{ strtoupper($item->first) }}</td>
                <td class="text-left">{{ ucwords($item->last) }}</td>
                <td class="text-left">{{ $item->genre == 'F' ? 'Feminin':'Masculin' }}</td>
                <td class="py-0 text-center">
                  <div class="d-flex m-0 p-0">
                    <input type="text" name="moyen1[]" class="form-control mx-0 input" value="{{ $item->cf }}" placeholder="---" style="width: 90px; background: none; font-size: 19px">
                    <span class="mt-2 px-1 d-flex" style="font-size: 19px"> / 20</span>
                  </div>
                </td>
                <td class="py-0 text-center">
                  <div class="d-flex m-0 p-0">
                    <input type="text" name="moyen2[]" class="form-control mx-0 input" value="{{ $item->og }}" placeholder="---" style="width: 90px; background: none; font-size: 19px">
                    <span class="mt-2 px-1 d-flex" style="font-size: 19px"> / 20</span>
                  </div>
                </td>
                <td class="py-0 text-center">
                  <div class="d-flex m-0 p-0">
                    <input type="text" name="moyen3[]" class="form-control mx-0 input" value="{{ $item->eo }}" placeholder="---" style="width: 90px; background: none; font-size: 19px">
                    <span class="mt-2 px-1 d-flex" style="font-size: 19px"> / 20</span>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <hr style="border: 2px solid">
        <input type="hidden" name="string" value="{{ $str }}">
        <input type="hidden" name="frensh" value="oui">
        <div class="text-center">
          <button type="button" class="btn btn-primary w-25 py-2" data-bs-toggle="modal" data-bs-target="#myModal">Valider From</button>
        </div>
      </div>
    </form>
  </div>
</div>
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