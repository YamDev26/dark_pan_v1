<?php

use App\Services\SuperAdminService;
use Livewire\Component;

new class extends Component
{
    public $data = [];
    public $edit = null;

    public function boot(SuperAdminService $srvs) {
        $this->srvs = $srvs;
    }

    public function mount() {
        $this->data = $this->srvs->country();
    }

    public function getEdit($id) {
        $this->edit = $this->srvs->getCountry($id);
    }
};
?>

<div>
    <div class="table-responsive">
        <table class="table text-start align-middle table-bordered table-hover mb-0">
            <thead>
                <tr class="text-white">
                    <th scope="col" class="text-center"></th>
                    <th scope="col" class="text-center">Libellé</th>
                    <th scope="col" class="text-center">Quantité</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
                        <td class="text-center">{{ ucwords($item->libelle) }}</td>
                        <td class="text-center">00</td>
                        <td class="text-center">
                            <span style="border-bottom: 2px solid {{ $item->status ? 'green':'red' }}">
                                {{ $item->status ? 'Actif':'Inactif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button" wire:click="getEdit({{ $item->id }})" class="btn btn-sm btn-sm-square btn-outline-warning my-0 p-1" data-bs-toggle="modal" data-bs-target="#myModal">
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
    <!-- Modal Add -->
    <div wire:ignore.self class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" >
            <div class="modal-content pb-0" style="background: #191C24">
                <form action="{{ route('country.edit') }}" method="post">
                    @csrf
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="myModalLabel">Edit Country</h5>
                    </div>
                    <div class="modal-body py-4" style="text-align: left">
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libelle Country<span class="text-primary">*</span> :</label>
                            <input type="text" name="libelle" class="form-control" id="libelle" value="{{ $edit ? ucwords($edit->libelle):null }}" placeholder="Libelle Country">
                            <input type="hidden" name="id" value="{{ $edit ? $edit->id:null }}">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="str" class="form-check-input" id="str" {{ $edit ? ($edit->status ? 'checked':null):'null' }}>
                            <label class="form-check-label" for="str">{{ $edit ? ($edit->status ? 'Activé':'Inactivé'):'null' }}</label>
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