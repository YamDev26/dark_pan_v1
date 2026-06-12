<div class="modal" id="tmpModal" tabindex="-1" aria-labelledby="tmpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">Emploi du temp</h5>
          <h5>2025/2026</h5>
          <h5>6eme1</h5>
        </div>
        <div class="modal-body py-4">
          <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
              <thead>
                <tr class="text-white">
                  <th scope="col" class="text-center">Horaires</th>
                  @foreach ($days as $day)
                    <th scope="col" class="text-center">{{ ucwords($day->libelle) }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td>01 Jan 2045</td>
                  <td>INV-0123</td>
                  <td>Jhon Doe</td>
                  <td>$123</td>
                  <td>Paid</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer mb-0 py-1">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Voir pdf</button>
        </div>
    </div>
  </div>
</div>