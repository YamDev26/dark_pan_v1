@foreach ($slots as $slot)
  <tr>
    <th class="text-center pb-0">
      {{ "{$slot['dbt']} - {$slot['fin']}" }}
    </th>
    @foreach ($days as $day)
      @php
        $isWednesdayAfternoon =
          $period === 'Après midi'
          && strtolower($day->libelle) === 'mercredi';
      @endphp
      <td class="pb-0">
        @unless ($isWednesdayAfternoon)
          <select name="select[]" class="form-select" style="background: none">
            <option value="">---</option>
            @forelse ($matters as $matter)
              <option value="{{ implode('_', [
                $matter->id,
                $slot->id,
                $day->id,
                $period === 'Matin' ? 1 : 2
              ]) }}">
                {{ $matter->symbol }}
              </option>
            @empty
              <option value="" disabled>
                Aucune matière
              </option>
            @endforelse
          </select>
        @endunless
      </td>
    @endforeach
  </tr>
@endforeach