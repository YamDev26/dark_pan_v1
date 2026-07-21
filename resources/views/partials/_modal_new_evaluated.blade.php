<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ $url }}" method="post">
        @csrf
        <div class="modal-header pt-2 pb-0 mb-0">
          <h5 class="modal-title" id="myModalLabel">Nouvelle Evaluation</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-4">
          {{-- Champs cachés --}}
          <input type="hidden" name="matter" value="{{ $matter['id'] }}">
          <input type="hidden" name="classe" value="{{ $classe['id'] }}">
          <input type="hidden" name="cutting" id="cutting">
          @if ($matters)
          <div class="mb-3">
            <label class="col-form-label">Sous Matière<span class="text-danger">*</span> :</label>
            <div class="my-0">
              @foreach ($matters as $item)
                <div class="form-check form-check-inline mx-3" title="{{ ucwords($item['libelle']) }}">
                  <input type="radio" name="sub" class="form-check-input" id="sub-{{ $item['id'] }}" value="{{ $item['id'] }}" {{ $loop->first ? 'checked':'' }}>
                  <label class="form-check-label" for="sub-{{ $item['id'] }}">{{ $item['symbol'] }}</label>
                </div>
              @endforeach
            </div>
          </div>
          @endif
          <div class="mb-2">
            <label for="type" class="col-form-label">Type Evaluation<span class="text-danger">*</span> :</label>
            <select name="type" id="type" class="form-select mb-2">
              <option value="">Select ...</option>
              @foreach ($getType as $item)
                <option value="{{ $item['id'] }}">{{ ucwords($item['libelle']) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="date" class="col-form-label">Date Evaluation<span class="text-danger">*</span> :</label>
            <input type="date" name="date" class="form-control" id="date">
          </div>
          <div class="mb-0">
            <label class="col-form-label">Value Evaluation<span class="text-danger">*</span> :</label>
            <div class="my-0">
              @foreach ($value as $i => $int)
                <div class="form-check form-check-inline mx-3">
                  <input type="radio" name="value" class="form-check-input" id="int-{{ $i }}" value="{{ $int }}" {{ $i == 1 ? 'checked':'' }}>
                  <label class="form-check-label" for="int-{{ $i }}">{{ $int }}</label>
                </div>
              @endforeach
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