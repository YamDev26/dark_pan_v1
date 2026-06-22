@extends('pdf.app')
@section('title', 'Moyenne matiere '.$classe->libelle)
@section('link')
<style>
  .tableau {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }

  .tableau th,
  .tableau td {
    border: 0.3px solid #000;
    padding: 8px 5px;
    text-align: center;
  }

  .tableau th {
    font-weight: bold;
    background: #f5f5f5;
  }

  .tableau td:nth-child(2),
  .tableau td:nth-child(3),
  .tableau td:nth-child(4) {
    text-align: left;
  }
</style>
@endsection
@section('school_year', $classe->school_year->libelle)
@section('fond_page', 'MOYENNE MATIERE')
@section('content')
  <div class="document-title">
    LISTE MOYENNE FRANCAIS {{ $classe->libelle }}
  </div>

  <div class="content" style="margin-top: 30px">
    <table class="tableau">
      <thead>
        <tr>
          <th></th>
          <th>Matricule</th>
          <th>Nom</th>
          <th>Prénoms</th>
          <th>Sexe</th>
          <th>CF</th>
          <th>OG</th>
          <th>EO</th>
          <th>Moyenne</th>
          <th>Rang</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $i => $item)
          <tr>
            <th>{{ $i < 9 ? '0'.$i+1:$i+1 }}</th>
            <td class="text-left">{{ $item->matricul }}</td>
            <td class="text-left">{{ strtoupper($item->first) }}</td>
            <td class="text-left">{{ ucwords($item->last) }}</td>
            <td>{{ $item->genre == 'F' ? 'Feminin':'Masculin' }}</td>
            <td>{{ $item->cf }}</td>
            <td>{{ $item->og }}</td>
            <td>{{ $item->eo }}</td>
            <td>{{ $item->moyenne }}</td>
            <td>{{$item->rang }}</td>
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