@extends('app')
@section('title', 'Emploi Du Temps '.$classe['libelle'])

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Emploi du temps</h4>
          <h4 class="mb-0">{{ $classe['libelle'] }}</h4>
          <div class="d-flex">
            <a href="{{ route('classe.create', $classe['id']) }}" class="btn btn-outline-primary mx-2 py-1">Edit</a>
            <a href="{{ route('classe.list', $classe['id']) }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-4">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle" id="myTable">
                <thead>
                  <tr class="text-white">
                    <th scope="col">Horaire</th>
                    @foreach ($days as $day)
                      <th scope="col">{{ ucwords($day->libelle) }}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {

    
  })
</script>
@endsection