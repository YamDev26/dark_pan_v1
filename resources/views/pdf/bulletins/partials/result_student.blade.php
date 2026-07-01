<tr>
  <th colspan="2" class="discipline">TOTAL</th>
  <th>{{ $student->values }}</th>
  <th>{{ sprintf('%05.2f', $student->total) }}</th>
  <th colspan="4" style="font-size: 14px; letter-spacing: 0.3px;">
    <span style="margin-right: 10px">
      Moyenne : {{ $student->moyenne }}/20
    </span>  ~
    <span style="margin-left: 10px">
      Rang : {{ $student->rang}}/15
    </span>
  </th>
</tr>
<tr>
  <td colspan="8">
    <table class="mytable" style="width: 100%; border-collapse:collapse;">
      <tbody>
        <tr>
          <td>Total absence : <strong>{{ sprintf('%02d', $student->totals) }}</strong> h</td>
          <td>Justifiée : <strong>{{ sprintf('%02d', $student->absens1) }}</strong> h</td>
          <td>Non justifiée : <strong>{{ sprintf('%02d', $student->absens2) }}</strong> h</td>
          <td colspan="3">Appréciations : ---- </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>