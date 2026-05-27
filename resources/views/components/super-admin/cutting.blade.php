<?php

use App\Services\SuperAdminService;
use Livewire\Component;

new class extends Component
{
    public $year;
    public $data = [];
    public $cutting = [];
    public function boot(SuperAdminService $srvs) {
        $this->srvs = $srvs;
    }

    public function mount() {
        $dts = $this->srvs->getCutting($this->year->id);
        $this->data = $dts;
        sizeof($dts) ? []:$this->cutting = $this->srvs->cutting();
    }

    public function status($val) {
        $tble = [1 => 'En attante', 2 => 'En cours', 3 => 'Terminé'];
        return $tble[$val];
    }

    public function color($val) {
        $tble = [1 => 'yellow', 2 => 'green', 3 => 'red'];
        return $tble[$val];
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
                    <th scope="col" class="text-center">Date debut</th>
                    <th scope="col" class="text-center">Date fin</th>
                    <th scope="col" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
                        <td class="text-center">{{ ucwords($item->cutting->libelle) }}</td>
                        <td class="text-center">{{ date('d.m.Y', strtotime($item->debut)) }}</td>
                        <td class="text-center">{{ date('d.m.Y', strtotime($item->fin)) }}</td>
                        <td class="text-center">
                            <span style="border-bottom: 2px solid {{ $this->color($item->status) }}">{{ $this->status($item->status) }}</span>
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
    <div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content pb-0" style="background: #191C24;">
                <form action="{{ route('cutting.create') }}" method="post">
                    @csrf
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="myModalLabel">Add Cutting</h5>
                        <strong style="font-size: 19px">{{ $year->libelle }}</strong>
                    </div>
                    <div class="modal-body py-4">
                        <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
                        <div class="table-responsive">
                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                <thead>
                                    <tr class="text-white">
                                        <th scope="col"></th>
                                        <th scope="col">Libellé</th>
                                        <th scope="col">Date debut <span class="text-danger">*</span></th>
                                        <th scope="col">Date fin <span class="text-danger">*</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <input type="hidden" name="year" value="{{ $year->id }}">
                                    @php $i = 1 @endphp
                                    @forelse ($cutting as $item)
                                    <tr>
                                        <td class="text-center">{{ '0'.$i++ }}</td>
                                        <td>
                                            {{ ucwords($item->libelle) }}
                                            <input type="hidden" name="str[]" value="{{ $item->id }}">
                                        </td>
                                        <td class="py-0">
                                            <input type="date" name="dbt[]" class="form-control" aria-describedby="emailHelp">
                                        </td>
                                        <td class="py-0">
                                            <input type="date" name="fin[]" class="form-control" aria-describedby="emailHelp">
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Valeurs non defini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer mb-0">
                        <button type="button" class="btn btn-outline-primary py-1 mx-2" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
                        <button type="submit" class="btn btn-outline-light py-1 mx-2" style="font-size: 14px">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content pb-0" style="background: #191C24;">
                <form action="{{ route('cutting.edit') }}" method="post">
                    @csrf
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="editModalLabel">Edit Cutting</h5>
                        <strong style="font-size: 19px">{{ $year->libelle }}</strong>
                    </div>
                    <div class="modal-body py-4">
                        <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
                        <div class="table-responsive">
                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                <thead>
                                    <tr class="text-white">
                                        <th scope="col"></th>
                                        <th scope="col">Libellé</th>
                                        <th scope="col">Date debut <span class="text-danger">*</span></th>
                                        <th scope="col">Date fin <span class="text-danger">*</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @forelse ($data as $item)
                                    <tr>
                                        <td class="text-center">{{ '0'.$i++ }}</td>
                                        <td>
                                            {{ ucwords($item->cutting->libelle) }}
                                            <input type="hidden" name="str[]" value="{{ $item->id }}">
                                        </td>
                                        <td class="py-0">
                                            <input type="date" name="dbt[]" class="form-control" value="{{ $item->debut }}">
                                        </td>
                                        <td class="py-0">
                                            <input type="date" name="fin[]" class="form-control" value="{{ $item->fin }}">
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Valeurs non defini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer mb-0">
                        <button type="button" class="btn btn-outline-primary py-1 mx-2" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
                        <button type="submit" class="btn btn-outline-light py-1 mx-2" style="font-size: 14px">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>