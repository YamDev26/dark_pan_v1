<div class="modal" id="DlteModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header py-2">
        <h5 class="modal-title" id="myModalLabel">Suppression</h5>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body py-4">
        <div class="text-center">
          <strong id="dtleName" style="font-size: 21px"></strong>
          <p style="font-size: 18px"><span id="dtleGenre"></span>  ~ <span id="dtleMatricul"></span></p>
          <h4 class="mt-0" id="dtleClass"></h4>
          Cette action peut avoir des modifictions majeurs !
          <p>Cliquez sur 'Oui' pour continuez.</p>
          <i class="fa fa-trash" style="font-size: 30px"></i>
        </div>
      </div>
      <form action="{{ route('register.delete') }}" method="post">
        @csrf
        <input type="hidden" name="id" id="idDelete">
        <div class="modal-footer mb-0">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>