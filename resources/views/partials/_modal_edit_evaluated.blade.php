<div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="#" method="post" id='formEdit'>
        @csrf
        @method('put')
        <div class="modal-header pt-2 pb-0 mb-0">
          <h5 class="modal-title" id="myModalLabel">Edit Evaluation</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-4">
          {{-- Champs cachés --}}
          <input type="hidden" name="evaluat" id="evaluated">
          @if ($matters)
          <div class="mb-3">
            <label class="col-form-label">Sous Matière<span class="text-danger">*</span> :</label>
            <div class="my-0">
              @foreach ($matters as $item)
                <div class="form-check form-check-inline mx-3" title="{{ ucwords($item['libelle']) }}">
                  <input type="radio" name="subE" class="form-check-input" id="subE-{{ $item['id'] }}" value="{{ $item['id'] }}" {{ $loop->first ? 'checked':'' }}>
                  <label class="form-check-label" for="subE-{{ $item['id'] }}">{{ $item['symbol'] }}</label>
                </div>
              @endforeach
            </div>
          </div>
          @endif
          <div class="mb-2">
            <label for="typeEdit" class="col-form-label">Type Evaluation<span class="text-danger">*</span> :</label>
            <select name="type" id="typeEdit" class="form-select mb-2">
              <option value="">Select ...</option>
              @foreach ($getType as $item)
                <option value="{{ $item['id'] }}">{{ ucwords($item['libelle']) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="dateEdit" class="col-form-label">Date Evaluation<span class="text-danger">*</span> :</label>
            <input type="date" name="date" class="form-control" id="dateEdit">
          </div>
          <div class="d-flex justify-content-between">
            <div class="mb-0">
              <label class="col-form-label">Value Evaluation<span class="text-danger">*</span> :</label>
              <div class="my-0">
                @foreach ($value as $i => $int)
                  <div class="form-check form-check-inline mx-3">
                    <input type="radio" name="note" class="form-check-input" id="intE-{{ $i }}" value="{{ $int }}" {{ $i == 1 ? 'checked':'' }}>
                    <label class="form-check-label" for="intE-{{ $i }}">{{ $int }}</label>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="my-0">
              <label for="status" class="col-form-label">Status Evaluation<span class="text-danger">*</span> :</label>
              <div class="my-0 mx-2">
                <input type="checkbox" name="status" class="form-check-input" id="status">
                <label class="form-check-label" for="status" id='libEdit'></label>
              </div>
            </div>
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