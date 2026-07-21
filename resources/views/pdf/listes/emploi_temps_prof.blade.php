@extends('pdf.app')
@section('title', 'Emploi du temps prof')
@section('link')
<style>
  .tableau {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
  }

  .tableau th,
  .tableau td {
    border: 0.3px solid #000;
    padding: 10px;
    text-align: center;
  }

  .tableau th {
    font-weight: bold;
    background: #f5f5f5;
  }

</style>
@endsection
@section('school_year', $year)
@section('fond_page', 'EMPLOI DU TEMPS')
@section('content')
  <div class="document-title" style="margin-top: 20px">
    EMPLOI DU TEMPS ENSEIGNANT
  </div>
  <div class="content" style="margin-top: 50px">
    <table class="tableau">
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
    <div class="teacher" style="float: right; margin-top: 12px;">
      <span style="text-decoration: underline">Prof :</span>
      <strong>{{ ucwords($teacher['civility']).' '.strtoupper($teacher['first_name']).' '.ucwords($teacher['last_name']) }}</strong>
    </div>
  </div>
@endsection