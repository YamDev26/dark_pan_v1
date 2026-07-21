<?php

use Livewire\Component;
use App\Services\DashboardService;

new class extends Component
{
    public $data = []; public $times = []; public $days = [];
    public $classe = 0;
    
    public function boot(DashboardService $service) {
        $this->service = $service;
    }


    public function mount() {
        $this->times = $this->service->getTime();
        $this->days = $this->service->getDayWeek();
        $this->data = $this->service->getTableTime();
        $this->classe = $this->service->nbreClasseTeacher();
    }
};
?>

<div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded p-4">
            <div class="d-flex align-items-center justify-content-between pb-2 mb-4" style="border-bottom: 1px solid #6C7293">
                <h4 class="mb-0">Emploi du temps</h4> 
                <a href="{{ route('pdf') }}" target="black">Voir pdf</a>
            </div>
            <div class="table-responsive">
                <div class="mb-2" style="font-size: 13px">
                    Vous disposez de {{ sprintf('%02d', count($data)) }}h par semaine pour ({{ sprintf('%02d', $classe) }}) classes.
                </div>
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-white">
                        <th scope="col" class="text-center">Horaire</th>
                        @foreach ($days as $day)
                        <th scope="col" class="text-center">{{ ucwords($day->libelle) }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach (['Matin' => $times[0], 'Après midi' => $times[1]] as $period => $slots)
                        @if (! $loop->first)
                        <tr>
                            <th colspan="{{ count($days) + 1 }}" class="text-center">
                            {{ $period }}
                            </th>
                        </tr>
                        @endif
                        @foreach ($slots as $slot)
                        <tr>
                            <td class="text-center pb-0">
                            {{ "{$slot['dbt']} - {$slot['fin']}" }}
                            </td>
                            @foreach ($days as $day)
                            @php
                                $isWednesdayAfternoon =
                                $period === 'Après midi'
                                && strtolower($day->libelle) === 'mercredi';
                            @endphp
                            <td class="text-center">
                                @unless ($isWednesdayAfternoon)
                                {{ getClasseTable($day->id, $slot->id, ($period === 'Matin' ? 1 : 2), $data) }}
                                @endunless
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>