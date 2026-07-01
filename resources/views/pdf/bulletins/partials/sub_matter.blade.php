@if ($sunMatter)
  @foreach($sunMatter as $sub)
    <tr>
      <td class="disciplines">{{ $sub->libelle }}</td>
      <td class="notes">{{ $sub->moyenne }}</td>
      <td>{{ $sub->values }}</td>
      <td>{{ sprintf('%05.2f', $sub->total) }}</td>
      <td>{{ $sub->rang }}</td>
      <td class="left">---</td>
      <td class="left">{{ mentionMsg($sub->moyenne) }}</td>
      <td></td>
    </tr>
  @endforeach
@endif