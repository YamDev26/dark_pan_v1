@extends('app')
@section('title', 'List Teacher')
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
      <h4 class="mb-0">Personnels</h4>
      <div class="d-flex">
        <a href="{{ route('user.create') }}" class="btn btn-outline-danger py-1 mx-2">Nouvel</a>
        <div>
          <select class="form-select form-select w-auto border-0 text-color-3" onchange="window.location.href=this.value;">
            <option value="">Choise ...</option>
            {{-- @foreach ($years as $item)
              <option value="{{ route('student.year', $item['id']) }}">{{ ucwords($item['libelle']) }}</option>
            @endforeach --}}
            <option value="{{ route('teacher.show') }}">disabled</option>
          </select>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle" id="myTable">
        <thead>
          <tr class="text-white">
            <th scope="col"></th>
            <th scope="col">Nom</th>
            <th scope="col">Prénoms</th>
            <th scope="col">Sexe</th>
            <th scope="col">Adresse Email</th>
            <th scope="col">Téléphone</th>
            <th scope="col">Discipline</th>
            <th scope="col" class="text-center">Action</th>
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
      ajax: '{{ route('user.data') }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'first', className: 'text-left'},
        {data: 'last', className: 'text-left'},
        {data: 'sexe', className: 'text-left'},
        {data: 'email', className: 'text-left'},
        {data: 'phon', className: 'text-left'},
        {data: 'profil', className: 'text-left'},
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