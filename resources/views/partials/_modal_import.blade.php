<div class="modal" id="AddModal" tabindex="-1" aria-labelledby="AddModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ $url }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-header pt-2 pb-1">
          <h5 class="modal-title" id="myModalLabel">Importation</h5>
          @if ($export)
            <a href="{{ $export }}" class="btn btn-outline-light my-0 py-1 px-1" title="New File" style="font-size: 15px">
              Exemplaire
            </a>
          @else
            <h3><i class="fa fa-user-edit text-primary"></i></h3>
          @endif
        </div>
        <div class="modal-body py-4">
          <div class="mb-2 px-3">
            <span class="d-flex justify-content-between">
              <label for="file" class="col-form-label">Chargez le fichier<span class="text-danger">*</span> :</label>
            </span>
            <input type="file" name="file" class="form-control bg-dark" id="file">
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