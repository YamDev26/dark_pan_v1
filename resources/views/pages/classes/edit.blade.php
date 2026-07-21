@extends('app')
@section('title', 'Add teacher '.$classe['libelle'])

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Teacher</h4>
          <h4 class="mb-0">{{ $classe['libelle'] }}</h4>
          <div class="d-flex">
            <a href="{{ route('classe.list', $classe['id']) }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="mt-2 mb-0">
          <div class="bg-secondary rounded">
            <form action="{{ route('classe.teaches', $classe['id']) }}" method="post" id="myForm">
              @csrf
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="myTable">
                  <thead>
                    <tr class="text-white">
                      <th scope="col"></th>
                      <th scope="col" class="text-center">Discipline</th>
                      <th scope="col" class="text-center">Enseignant</th>
                      <th scope="col" class="text-center">Prof principal</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($matters as $i => $matter)
                      <tr>
                        <th scope="col" class="text-center">{{ $i < 10 ? '0'.$i+1:$i+1 }}</th>
                        <th>
                          {{ $matter->symbol }}
                          <input type="hidden" name="matter[]" value="{{ $matter->id }}">
                        </th>
                        <th>
                          <select name="teacher[]" class="form-select mb-0" style="background: none">
                            <option value="">---</option>
                            @foreach ($users as $user)
                              <option value="{{ $user->id.'_'.$i+1 }}" {{ $user->matter == $matter->id ? 'selected':'' }}>
                                {{ ucwords($user->civility).' '.strtoupper($user->first_name).' '.ucwords($user->last_name) }}
                              </option>
                            @endforeach
                          </select>
                        </th>
                        <th class="text-center">
                          <input class="form-check-input" type="radio" name="cheched" value="{{ $i+1 }}">
                        </th>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <hr class="mt-1" style="border: 1px solid">
              <div class="text-center mb-O">
                <button type="button" class="btn btn-primary w-25 py-1" data-bs-toggle="modal" data-bs-target="#myModal">Valider</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header pt-2 pb-1">
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body py-4">
        <div class="text-center">
          Cette action peut avoir des modifictions majeurs !
          <p>Cliquez sur 'VALIDER' pour continuez.</p>
          <i class="fa fa-check-circle" style="font-size: 30px"></i>
        </div>
      </div>
      <div class="modal-footer mb-0">
        <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
        <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {

    $('button[type="submit"]').on('click', function () {
      $('#myForm').submit(); // Envoie du formulaire
    });
    
  })
</script>
@endsection