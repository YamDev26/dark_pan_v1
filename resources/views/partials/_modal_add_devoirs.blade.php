<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('devoirs.store') }}" method="post">
        @csrf
        <div class="modal-header pt-2 pb-0 mb-0">
          <h5 class="modal-title" id="myModalLabel">Nouveau devoir</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-4">
          {{-- Champs cachés --}}
          <input type="hidden" name="cutting" id="cutting">
          <div class="mb-3">
            <label class="col-form-label">Type devoir<span class="text-danger">*</span> :</label>
            <div class="my-0">
              @foreach ($types as $item)
                <div class="form-check form-check-inline mx-2">
                  <input type="radio" name="type" class="form-check-input" id="sub-{{ $item['id'] }}" value="{{ $item['id'] }}" {{ $loop->first ? 'checked':'' }}>
                  <label class="form-check-label" for="sub-{{ $item['id'] }}">{{ ucfirst($item['libelle']) }}</label>
                </div>
              @endforeach
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 mb-2">
              <label for="getClasse" class="col-form-label">Classe<span class="text-danger">*</span> :</label>
              <select name="classe" id="getClasse" class="form-select mb-2">
                <option value="">Select ...</option>
                @foreach ($classes as $item)
                  <option value="{{ $item->id }}">{{ ucwords($item->libelle) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-6 mb-2">
              <label for="date" class="col-form-label">Date<span class="text-danger">*</span> :</label>
              <input type="date" name="date" class="form-control" id="date">
            </div>
            <div class="col-sm-6">
              <label for="times" class="col-form-label">Durée<span class="text-danger">*</span> :</label>
              <input type="text" name="times" class="form-control" id="times" placeholder="45min, 1h, 1h30, ...">
            </div>
            <div class="col-sm-6">
              <label for="debut" class="col-form-label">Heure<span class="text-danger">*</span> :</label>
              <input type="time" name="debut" class="form-control" id="debut">
            </div>
          </div>
          <div class="mt-3">
            <label class="col-form-label">Discipline<span class="text-danger">*</span> :</label>
            <span id="listMatter" class="mx-2">--- --- ---</span>
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