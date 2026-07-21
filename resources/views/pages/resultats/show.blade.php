@extends('app')
@section('title', 'Resultat List '.$classe['libelle'])
@section('link')
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
    <form action="{{ route('resultat.pdf', $classe['id'].'_'.$cutting['id']) }}" method="post" target="_black" id="myForm">
      @csrf
      <div class="d-flex align-items-center justify-content-between mb-4 pb-2" style="border-bottom: 1px solid #6C7293">
        <h4 class="mb-0">{{ ucwords($cutting['cutting']['libelle']) }}</h4>
        <div class="my-0">
          <h4 class='my-0'>{{ $classe['libelle'] }}</h4>
          {{-- <span class="my-0">Resultat</span> --}}
        </div>
        <div class="d-flex">
          <button type="submit" class="btn btn-outline-danger mx-2 py-1">Generate</button>
          <a href="{{ route('resultat.show', $classe['id'].'_'.$cutting['id']) }}" class="btn btn-outline-light py-1">Return</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
          <thead>
            <tr class="text-white">
              <th scope="col" class="text-center"></th>
              <th scope="col" class="text-center">Matricule</th>
              <th scope="col" class="text-center">Nom</th>
              <th scope="col" class="text-center">Prenoms</th>
              <th scope="col" class="text-center">Genre</th>
              <th scope="col" class="text-center">Moyenne</th>
              <th scope="col" class="text-center">Rang</th>
              <th scope="col" class="text-center">
                <input type="checkbox" class="form-check-input" id="checkAll">
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $i => $item)
              <tr>
                <td class="text-center">{{ sprintf('%02d', $i + 1) }}</td>
                <td class="text-left">{{ $item->matricul }}</td>
                <td class="text-left">{{ strtoupper($item->first) }}</td>
                <td class="text-left">{{ ucwords($item->last) }}</td>
                <td class="text-center">{{ $item->genre == 'F' ? 'Feminin':'Masculin' }}</td>
                <td class="text-center">{{ $item->moyenne }}</td>
                <td class="text-center">{{ $item->rang }}</td>
                <td class="py-0 text-center">
                  <input type="checkbox" name="student[]" class="form-check-input row-check" value="{{ $item->id }}">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </form>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    const table = $('#myTable').DataTable({
      processing: true,
      ordering: false,
    });

    
    // $('#checkAll').on('change', function () {
    //   const checked = $(this).prop('checked');
    //   table.$('.row-check').prop('checked', checked);
    // });


    $("#checkAll").on("change", function(){
      var checked = this.checked;
      table.rows({ page: 'current' }).every(function () {
        $(this.node()).find('.row-check').prop('checked', checked);
      });
    });


    $('button[type="submit"]').on('click', function(e){
      e.preventDefault();
      let tags = [];
      $('input[name="student[]"]:checked').each(function() {
        tags.push($(this).val());
      });

      if(tags.length){
        $('#myForm').submit(); // e.target refers to the form element
      }
      else{
        alertify.error('Une erreur est survenue.');
      }
    });


    table.on('page.dt', function () {
      $('#checkAll').prop('checked', false);
    });


  })
</script>
@endsection