@extends('app')
@section('title', 'Slot Time '.($data ? 'Edit':'Create'))
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-12 px-4">
      <div class="bg-secondary rounded h-100 p-4">
        <div class="mb-4 d-flex justify-content-between">
          <h5 class="m-2">{{ $data ? 'Edit':'Create' }} School</h5>
          <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
          <a href="{{ route('slot.index') }}" class="btn btn-outline-light m-2 py-0">Return</a>
        </div>
        <form action="{{ route('slot.store') }}" method="POST" id="myForm">
          @csrf
          <div class="row">
            <div class="col-12 col-sm-6">
              <div class="d-flex justify-content-around">
                <h6>Matinnée</h6>
                <hr class="w-75" style="border: 2px solid">
              </div>
              <div class="row">
                @php $i = 1; @endphp
                @while ($i <= 5)
                  <div class="col-4 mb-2 pt-4" style="text-align: right">
                    <label class="col-form-label">
                      {{ $i > 1 ? $i.'eme':$i.'ère' }} Heure :
                    </label>
                  </div>
                  <div class="col-4 mb-2">
                    <label class="col-form-label" for="slot_1_debt_{{ $i }}">Debut :</label>
                    <input type="time" name="debt1[]" class="form-control" id="slot_1_debt_{{ $i }}">
                  </div>
                  <div class="col-4 mb-2">
                    <label class="col-form-label" for="slot_1_fin_{{ $i }}">Fin :</label>
                    <input type="time" name="fin1[]" class="form-control" id="slot_1_fin_{{ $i }}">
                  </div>
                  @php $i++; @endphp
                @endwhile
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="d-flex justify-content-around">
                <h6>Après Midi</h6>
                <hr class="w-75" style="border: 2px solid">
              </div>
              <div class="row">
                @php $i = 1; @endphp
                @while ($i <= 5)
                  <div class="col-4 mb-2 pt-4" style="text-align: right">
                    <label class="col-form-label">
                      {{ $i > 1 ? $i.'eme':$i.'ère' }} Heure :
                    </label>
                  </div>
                  <div class="col-4 mb-2">
                    <label class="col-form-label" for="slot_2_debt_{{ $i }}">Debut :</label>
                    <input type="time" name="debt2[]" class="form-control" id="slot_2_debt_{{ $i }}">
                  </div>
                  <div class="col-4 mb-2">
                    <label class="col-form-label" for="slot_2_fin_{{ $i }}">Fin :</label>
                    <input type="time" name="fin2[]" class="form-control" id="slot_2_fin_{{ $i }}">
                  </div>
                  @php $i++; @endphp
                @endwhile
              </div>
            </div>
          </div>
          <hr style="border: 2px solid">
          <div class="text-center">
            <button type="button" class="btn btn-primary w-25 py-1" data-bs-toggle="modal" data-bs-target="#myModal">Valider</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header py-2">
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

    // Uniquement que les chiffres ----
    $('#phon').on('input', function() {
      this.value = this.value.replace(/\D/g, '');
    });


    $('input[name="cycle2"]').on('change', function () {
      verifyCheckbox($(this).is(':checked'), 2);
    });

    $('input[name="cycle1"]').on('change', function () {
      verifyCheckbox($(this).is(':checked'), 1);
    });

    $('button[type="submit"]').on('click', function () {
      $('#myForm').submit(); // Envoie du formulaire
    });


    // Function 
    function verifyCheckbox($check, $valeur) {
      $check ? 
      $('#date'+$valeur).prop('disabled', false):
      $('#date'+$valeur).prop('disabled', true);
    }
  })
</script>
@endsection