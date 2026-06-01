<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="min-width: 600px">
    <div class="modal-content" style="background: #191C24">
      <div class="modal-header pb-1">
        <h4 class="modal-title" id="myModalLabel">Inscription</h4>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body pb-0">
        <div id="block1" style="display:none">
          <h5 class="text-center" id="addName"></h4>
          <div class="row">
            <div class="col-4 text-center">
              <img class="img-fluid mx-auto mt-2" src="{{ asset('assets/img/testimonial-2.jpg') }}" style="width: 100px; height: 90px; border:1px solid; border-radius: 5px">
            </div>
            <div class="col-8 pt-4">
              <p class="mb-1" id="addGenre" style="font-size: 17px"></p>
              <p class="mb-1" id="addNaiss" style="font-size: 17px"></p>
              <p class="mb-1" id="addMtcl" style="font-size: 17px"></p>
            </div>
          </div>
          <hr class="mt-0">
          <div class="text-center" id="existeInpts" style="display:none">
            <p>Inscription effectuée</p>
            <h4 id="classeTrble">6eme1</h4>
          </div>
          <div class="row px-3 pb-0">
            <div class="col-3 mb-3">
              <label for="#" class="form-label">Affecté<span class="text-danger">*</span> :</label> <br>
              <div class="form-check form-check-inline">
                <input type="radio" name="affecte" class="form-check-input" id="aftNon" value="non">
                <label class="form-check-label" for="aftNon" style="font-size: 12px">Non</label>
              </div>
              <div class="form-check form-check-inline">
                <input type="radio" name="affecte" class="form-check-input" id="aftOui" value="oui" checked>
                <label class="form-check-label" for="aftOui" style="font-size: 12px">Oui</label>
              </div>
            </div>
            <div class="col-3 mb-3">
              <label for="#" class="form-label">Rédoublant<span class="text-danger">*</span> :</label> <br>
              <div class="form-check form-check-inline">
                <input type="radio" name="redoublant" class="form-check-input" id="rbtNon" value="non" checked>
                <label class="form-check-label" for="rbtNon" style="font-size: 12px">Non</label>
              </div>
              <div class="form-check form-check-inline">
                <input type="radio" name="redoublant" class="form-check-input" id="rbtOui" value="oui">
                <label class="form-check-label" for="rbtOui" style="font-size: 12px">Oui</label>
              </div>
            </div>
            <div class="col-3 mb-3">
              <label for="#" class="form-label">Boursier<span class="text-danger">*</span> :</label> <br>
              <div class="form-check form-check-inline">
                <input type="radio" name="boursier" class="form-check-input" id="brsNon" value="non" checked>
                <label class="form-check-label" for="brsNon" style="font-size: 12px">Non</label>
              </div>
              <div class="form-check form-check-inline">
                <input type="radio" name="boursier" class="form-check-input" id="brsOui" value="oui">
                <label class="form-check-label" for="brsOui" style="font-size: 12px">Oui</label>
              </div>
            </div>
            <div class="col-3 mb-3">
              <label for="#" class="form-label">Interne<span class="text-danger">*</span> :</label> <br>
              <div class="form-check form-check-inline">
                <input type="radio" name="interne" class="form-check-input" id="intNon" value="non" checked>
                <label class="form-check-label" for="intNon" style="font-size: 12px">Non</label>
              </div>
              <div class="form-check form-check-inline">
                <input type="radio" name="interne" class="form-check-input" id="intOui" value="oui">
                <label class="form-check-label" for="intOui" style="font-size: 12px">Oui</label>
              </div>
            </div>

            <div class="col-6 mb-2">
              <label for="level" class="form-label">Niveau actuel<span class="text-danger">*</span> :</label>
              <select name="level" class="form-select mb-3" aria-label="Default select example">
                <option selected="">Select ...</option>
                @foreach ($levels as $level)
                  <option value="{{ $level['id'] }}" data-symbol="{{ $level['symbol'] }}">{{ $level['symbol'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6 mb-2">
              <label for="#" class="form-label mb-3">Langue vivante 2<span class="text-danger">*</span> :</label> <br>
              <div class="form-check form-check-inline">
                <input type="radio" name="lv2" class="form-check-input" id="lv2All" value="all" checked>
                <label class="form-check-label" for="lv2All">Allemand</label>
              </div>
              <div class="form-check form-check-inline">
                <input type="radio" name="lv2" class="form-check-input" id="lv2Esp" value="esp">
                <label class="form-check-label" for="lv2Esp">Espagnol</label>
              </div>
            </div>
            <div class="col-6 mb-2">
              <label for="classe" class="form-label">Série<span class="text-danger">*</span> :</label>
              <select name="classe" class="form-select mb-3" aria-label="Default select example">
                <option selected="">Select ...</option>
              </select>
            </div>
            <div class="col-6 mb-2">
              <label for="classe" class="form-label">Classe actuelle<span class="text-danger">*</span> :</label>
              <select name="classe" class="form-select mb-3" aria-label="Default select example">
                <option selected="">Select ...</option>
              </select>
            </div>
          </div>
        </div>
        <div class="text-center py-3" id="block2">
          <i class="fa fa-times-circle" style="font-size: 60px; color:red"></i>
          <h5 class="mt-2" style="color: #6C7293">
            <p class="mb-1">Matricule Introuvable</p>
            <strong id="uderMatricul"></strong>
          </h4>
        </div>
      </div>
      <div class="modal-footer my-0 py-0">
        <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
        <button type="button" class="btn btn-outline-light py-1" id="subMit" style="font-size: 14px">Imprim</button>
      </div>
    </div>
  </div>
</div>