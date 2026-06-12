<div class="modal" id="DtailModal" tabindex="-1" aria-labelledby="DtailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 600px">
    <div class="modal-content" style="background: #191C24">
      <div class="modal-header pt-2 pb-0 mb-0">
        <h5 class="modal-title" id="myModalLabel">Détail</h5>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body">
        <h5 class="text-center" id="dtlName"></h4>
        <div class="row">
          <div class="col-4 text-center">
            <strong class="mb-3" id="dtlMatricul" style="font-size: 17px"></strong>
            <img class="img-fluid mx-auto mt-2" src="{{ asset('assets/img/testimonial-2.jpg') }}" style="width: 100px; height: 90px; border:1px solid; border-radius: 5px">
          </div>
          <div class="col-8 pt-4">
            <p class="mb-1" id="dtlGenre" style="font-size: 17px"></p>
            <p class="mb-1" id="dtlNaiss" style="font-size: 17px"></p>
            <p class="mb-1" id="dtlResident" style="font-size: 17px"></p>
          </div>
        </div>
        <hr/>
        <div>
          <div class="row">
            <div class="col-4 mb-2" id="dtlAffect"></div>
            <div class="col-4 mb-2" id="dtlRedoub"></div>
            <div class="col-4 mb-2" id="dtlBourse"></div>

            <div class="col-4 mb-2" id="dtlLevel"></div>
            <div class="col-4 mb-2" id="dtlSerie" style="display: none">Série : C</div>
            <div class="col-4 mb-2" id="dtlClasse"></div>

            <div class="col-4 mb-2" id="dtlLv2" style="display: none"></div>
            <div class="col-4 mb-2" id="dateIscte"></div>
          </div>
        </div>
      </div>
      <form action="#" method="get" id="myDetail" target="_blank">
        @csrf
        <div class="modal-footer my-0">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Imprim</button>
        </div>
      </form>
    </div>
  </div>
</div>