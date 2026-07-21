@extends('app')
@section('title', 'Emploi de temps prof')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-1" style="border-bottom: 1px solid #6C7293">
      <h4 class="mb-0">Emploi du temps</h4>
      <a href="{{ route('horraire.index') }}" class="btn btn-outline-light py-1">Return</a>
    </div>
    <div class="table-responsive">
      <div class="d-flex justify-content-between mb-2">
        Prof : {{ ucwords($teacher['civility']).' '.strtoupper($teacher['first_name']).' '.ucwords($teacher['last_name']) }}
        <a href="{{ route('horraire.pdf', $teacher['id']) }}" target="black">voir pdf</a>
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
@endsection
@section('script')
<script>
  $(document).ready(function() {
    
  })
</script>
@endsection