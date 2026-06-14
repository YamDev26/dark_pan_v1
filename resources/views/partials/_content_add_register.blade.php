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
    <label for="myLevel" class="form-label">Niveau actuel<span class="text-danger">*</span> :</label>
    <select name="level" class="form-select mb-3" id="myLevel">
      <option value="">Select ...</option>
      @foreach ($levels as $level)
        <option value="{{ $level['id'] }}" data-symbol="{{ $level['symbol'] }}">{{ $level['symbol'] }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-6 mb-2" id="divSerie" style="display: none">
    <label for="mySerie" class="form-label">Série<span class="text-danger">*</span> :</label>
    <select name="serie" class="form-select mb-3" id="mySerie">
      <option value="">Select ...</option>
    </select>
  </div>
  <div class="col-6 mb-2" id="divLv2" style="display: none">
    <label for="#" class="form-label mb-3">Langue vivante 2<span class="text-danger">*</span> :</label> <br>
    <div class="form-check form-check-inline">
      <input type="radio" name="lv2" class="form-check-input" id="lv2All" value="all">
      <label class="form-check-label" for="lv2All">Allemand</label>
    </div>
    <div class="form-check form-check-inline">
      <input type="radio" name="lv2" class="form-check-input" id="lv2Esp" value="esp">
      <label class="form-check-label" for="lv2Esp">Espagnol</label>
    </div>
  </div>
  <div class="col-6 mb-2">
    <label for="myClasse" class="form-label">Classe<span class="text-danger">*</span> :</label>
    <select name="classe" class="form-select mb-3" id="myClasse">
      <option value="">Select ...</option>
    </select>
  </div>
</div>