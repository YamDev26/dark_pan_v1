@extends('app')
@section('title', 'Emploi Du Temps '.$classe['libelle'])

@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Emploi du temps</h4>
          <h4 class="mb-0">{{ $classe['libelle'] }}</h4>
          <div class="d-flex">
            <select class="form-select form-select w-auto border-0 text-color-3 mx-2" onchange="window.location.href=this.value;">
              <option value="">...</option>
              <option value="{{ route('classe.create', $classe['id']) }}">Edit</option>
              <option value="#" data-option="pdf">pfd</option>
            </select>
            <a href="{{ route('classe.list', $classe['id']) }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead>
                  <tr class="text-white">
                    <th scope="col">Horaire</th>
                    @foreach ($days as $day)
                      <th scope="col">{{ ucwords($day->libelle) }}</th>
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
                              {{ getMatterTable($day->id, $slot->id, ($period === 'Matin' ? 1 : 2), $data) }}
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
    </div>
  </div>
</div>
@endsection