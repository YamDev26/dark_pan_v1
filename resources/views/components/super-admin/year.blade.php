<?php

use App\Services\SuperAdminService;
use Livewire\Component;

new class extends Component
{
    public $data;
    public $val = null;
    public function boot(SuperAdminService $srvs) {
        $this->srvs = $srvs;
    }

    public function mount() {
        $this->data = $this->srvs->getYear();
    }

    public function edit($id) {
        $this->val = $this->srvs->yearShool($id);
    }
};
?>

<div>
    <div class="table-responsive">
        <table class="table text-start align-middle table-bordered table-hover mb-0">
            <thead>
                <tr class="text-white">
                    <th scope="col" class="text-center"></th>
                    <th scope="col" class="text-center">Année Scolaire</th>
                    <th scope="col" class="text-center">Libellé</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
                        <td class="text-center">{{ $item->libelle }}</td>
                        <td class="text-center">{{ $item->decoupe > 2 ? 'Trimestre':'Semestre' }}</td>
                        <td class="text-center">
                            <span class="pcoded-badge label label-success">{{ $item->status ? 'Actif':'Inactif' }}</span>
                        </td>
                        <td class="text-center">
                            <button type="button" wire:click="edit({{ $item->id }})" class="btn btn-sm btn-sm-square btn-outline-warning my-0 p-1" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center py-2" colspan="5">
                            Underfined Data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Modal -->
    <div wire:ignore.self class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" >
            <div class="modal-content pb-0" style="background: #191C24">
            <form action="{{ route('school_year.edit', ($val ? $val->id:' null')) }}" method="post">
                @csrf
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="editModalLabel">Edit School Year</h5>
                    <strong style="font-size: 19px">{{ $val ? $val->libelle:'null' }}</strong>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3" style="text-align: left">
                        <label for="libEdit" class="form-label">Année Scolaire<span class="text-primary">*</span> :</label>
                        <input type="text" name="year" class="form-control" id="libEdit" value="{{ $val ? $val->libelle:'null' }}">
                    </div>
                    <div class="mb-0" style="text-align: left">
                        <label class="form-label">Découpage Scolaire <span class="text-primary">*</span> :</label><br>
                        <div class="d-flex align-items-center justify-content-between px-2">
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="radio" class="form-check-input" id="editRadio1" value="3" {{ $val ? ($val->decoupe == 3 ? 'checked':null):'null' }}>
                                    <label class="form-check-label" for="editRadio1">Trimmestre</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="radio" class="form-check-input" id="editRadio2" value="2" {{ $val ? ($val->decoupe == 2 ? 'checked':null):'null' }}>
                                    <label class="form-check-label" for="editRadio2">Semestre</label>
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="checked" class="form-check-input" id="edit1" {{ $val ? ($val->status ? 'checked':null):'null' }}>
                                <label class="form-check-label" for="edit1">Activé</label>
                            </div>
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
</div>