@extends('app')
@section('title', 'Get Evaluated')
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
      <h4 class="mb-0">Gestion Evaluation</h4>
      <h3><i class="fa fa-user-edit text-primary"></i></h3>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle" id="myTable">
        <thead>
          <tr class="text-white">
            <th scope="col" class="w-25"></th>
            <th scope="col" class="w-25">Libelle</th>
            <th scope="col" class="w-25">Effectif</th>
            <th scope="col" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('evaluated.show') }}" method="get">
        @csrf
        <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">Matter</h5>
          <h4 id="libelle"></h4>
        </div>
        <div class="modal-body py-4">
          <div class="mb-2">
            <input type="hidden" name="class" id="classId">
            <label for="select" class="col-form-label">Select<span class="text-danger">*</span> :</label>
            <select name="select" id="select" class="form-select mb-2">
                <option value="">Select ...</option>
            </select>
          </div>
        </div>
        <div class="modal-footer mb-0">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    $(document).on('click', '.btn-warning', function() {
      $id = $(this).data('id');
      $('.option').remove();
      $.ajax({
        url: '{{ route('evaluated.matter') }}',
        method: 'GET',
        data: { 
          id: $id 
        },
        success: function(data){
          console.log(data);
          $('#libelle').text(data['classe']['libelle']);
          $('#classId').val(data['classe']['id']);
          if(data['matters']) {
            $i = 0;
            while($i < data['matters'].length) {
              $('#select').append(
                '<option class="option" value="'+data['matters'][$i]['id']+'">'
                  +data['matters'][$i]['symbol']+
                '</option>'
              );
              $i++;
            }
          }
          $("#myModal").modal("show");
        }
      })
    });


    $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('evaluated.yajra') }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'libelle', className: 'text-center'},
        {data: 'effectif', className: 'text-center'},
        {data: 'action', className: 'text-center', orderable: false, searchable: false},
      ],
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });
  })
</script>
@endsection