@extends('pdf.app')
@section('title', 'Liste moyenne '.$classe['libelle'])
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
    padding: 15px 5px;
    text-align: center;
  }

  .tableau th {
    font-weight: bold;
    background: #f5f5f5;
  }

  .tableau td:nth-child(2),
  .tableau td:nth-child(3) {
    text-align: left;
  }

</style>
@endsection
@section('school_year', $classe->school_year->libelle)
@section('fond_page', 'LISTE MOYENNE CLASSE')
@section('content')
<div class="document-title" style="margin-top: 20px">
    LISTE MOYENNE CLASSE {{ strtoupper($cutting->cutting->libelle).' '.$classe->libelle }}
  </div>

  <div class="content" style="margin-top: 50px">
    <table class="tableau">
      <thead>
        <tr>
          <th></th>
          <th>Matricule</th>
          <th>Nom & Prénoms</th>
          <th>Genre</th>
          @foreach ($matters as $matter)
            <th>{{ $matter['symbol'] }}</th>
          @endforeach
          <th>Moyenne</th>
          <th>Rang</th>
        </tr>
      </thead>
      <tbody>
          @foreach ($datas as $i => $item)
            <tr>
              <th>{{ $i < 9 ? '0'.$i+1:$i+1 }}</th>
              <td>{{ $item['matricul'] }}</td>
              <td>{{ $item['name'] }}</td>
              <td>{{ ucwords($item['genre']) }}</td>
              @foreach ($matters as $matter)
                <td>{{ $item[$matter['symbol']] ?? '--' }}</td>
              @endforeach
              <td>
                <strong>{{ $item['moyenne'] }}</strong>
              </td>
              <td>{{ $item['rang'] }}</td>
            </tr>
          @endforeach
      </tbody>
    </table>
  </div>
@endsection
@section('num_page')
  <span class="page-number">
    {{-- {PAGE_NUM} / {PAGE_COUNT} --}}
  </span>
@endsection