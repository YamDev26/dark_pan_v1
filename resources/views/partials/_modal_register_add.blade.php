<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="min-width: 600px">
    <div class="modal-content" style="background: #191C24">
      <div class="modal-header pt-2 pb-0 mb-0">
        <h4 class="modal-title" id="myModalLabel">Inscription</h4>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <form action="{{ route('register.store') }}" method="post">
        @csrf
        <div class="modal-body pb-0">
          <div id="block1" style="display:none">
            <div class="my-0 text-center ">
              <h5 class="mb-0" id="addName"></h4>
              <span class="mb-1 mt-0" id="addMtcl" style="font-size: 17px"></span>
            </div>
            <div class="row">
              <div class="col-4 pt-0 text-center">
                <img class="img-fluid mx-auto" src="{{ asset('assets/img/student_2.png') }}" style="width: 100px; height: 90px; border:1px solid; border-radius: 5px">
              </div>
              <div class="col-8 pt-4">
                <p class="mb-1" id="addGenre" style="font-size: 17px"></p>
                <p class="mb-1" id="addNaiss" style="font-size: 17px"></p>
              </div>
            </div>
            <hr class="mt-0">
            <div class="text-center" id="yesIscrit" style="display:none">
              <i class="fa fa-check-circle" style="font-size: 60px; color:green"></i>
              <h5 class="mt-2" style="color: #6C7293">
                <p>Inscription effectuée</p>
                <strong id="classeTrble">6eme1</strong>
              </h4>
            </div>
            <div id="nonIscrit" style="display:none">
              @include('partials._content_add_register')
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
        <div class="modal-footer my-0">
          <input type="hidden" name="student" id='studentId'>
          <input type="hidden" name="matricule" id='inputMtl'>
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" id="btnStore" style="font-size: 14px" disabled>Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>