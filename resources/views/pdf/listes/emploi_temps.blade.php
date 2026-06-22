@extends('pdf.app')
@section('title', 'Emploi du temps '.$classe['libelle'])
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
@section('school_year', $classe->school_year->libelle)
@section('fond_page', 'EMPLOI DU TEMPS')
@section('content')
<div class="document-title" style="margin-top: 20px">
    EMPLOI DU TEMPS DE CLASSE {{ $classe->libelle }}
  </div>

  <div class="content" style="margin-top: 50px">
    <table class="tableau">
      <thead>
        <tr>
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
              <th colspan="{{ count($days) + 1 }}">
                {{ $period }}
              </th>
            </tr>
          @endif
          @foreach ($slots as $slot)
            <tr>
              <td>
                {{ "{$slot['dbt']} - {$slot['fin']}" }}
              </td>
              @foreach ($days as $day)
                @php
                  $isWednesdayAfternoon =
                    $period === 'Après midi'
                    && strtolower($day->libelle) === 'mercredi';
                @endphp
                <td>
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
@endsection