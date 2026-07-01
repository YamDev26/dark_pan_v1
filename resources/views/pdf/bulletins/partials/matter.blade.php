@foreach ($bilans as $i => $bilan)
  @foreach ($matters[$bilan->id] ?? [] as $matter)
    <tr>
      <td class="disciplines">{{ $matter->libelle }}</td>
      <td class="notes">{{ $matter->moyenne }}</td>
      <td>{{ $matter->values }}</td>
      <td>{{ sprintf('%05.2f', $matter->total) }}</td>
      <td>{{ $matter->rang }}</td>
      <td class="left">
        {{ ucwords($matter->civility).' '.strtoupper($matter->first_name).' '.formatName($matter->last_name) }}
      </td>
      <td class="left">{{ mentionMsg($matter->moyenne) }}</td>
      <td></td>
    </tr>
  @endforeach

  <tr style="background: #f5f5f5;">
    <th class="discipline">{{ ucwords($bilan->libelle) }}</th>
    <th class="note">{{ $bilan->moyenne }}</th>
    <th>{{ $bilan->values }}</th>
    <th>
      {{ sprintf('%05.2f', $bilan->total)  }}
    </th>
    <th>{{ $bilan->rang }}</th>
    <th colspan="3"></th>
  </tr>
@endforeach