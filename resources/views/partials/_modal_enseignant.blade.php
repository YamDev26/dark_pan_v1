<div class="modal" id="profModal" tabindex="-1" aria-labelledby="profModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">Liste enseignant</h5>
          <h5>{{ $classe['libelle'] }}</h5>
        </div>
        <hr>
        <div class="modal-body pt-4 pb-0">
          <div class="table-responsive mb-3">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
              <thead>
                <tr class="text-white">
                  <th scope="col"></th>
                  <th scope="col" class="text-left">Enseignant</th>
                  <th scope="col" class="text-left">Discipline</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $i => $user)
                  <tr>
                    <td scope="col" class="text-center">
                      {{ $i+1 < 10 ? '0'.$i+1:$i+1 }}
                    </td>
                    <td>
                      {{ ucwords($user->civility).' '.strtoupper($user->first_name).' '.ucwords($user->last_name) }}
                      <span class="text-danger">{{ $user->checked ? '*':''  }}</span>
                    </td>
                    <td>
                      {{ $user->symbol }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">Non defini</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="mb-1" style="font-size: 12px">
            NB : Professeur Principal (<span class="text-danger">*</span>)
          </div>
        </div>
        <div class="modal-footer mb-0 py-1">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <a href="{{ route('classe.teacher', $classe['id']) }}" class="btn btn-outline-light py-1" style="font-size: 14px">Editer</a>
        </div>
    </div>
  </div>
</div>