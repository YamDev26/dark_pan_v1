<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Fiche Note {{ $evaluat['level_matter']['matter']['symbol'] }}</title>
</head>
<body>
  <table class="table">
    <thead>
      <tr>
        <th style="width: 60px; height: 25px; text-align: center">N°</th>
        <th style="width: 100px; height: 25px; text-align: center">Matricule</th>
        <th style="width: 150px; height: 25px; text-align: center">Nom</th>
        <th style="width: 300px; height: 25px; text-align: center">Prenoms</th>
        <th style="width: 100px; height: 25px; text-align: center">Genre</th>
        <th style="width: 100px; height: 25px; text-align: center">Note</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $index => $item )
        <tr>
        <th style="height: 30px; text-align: center">{{ $index < 9 ? '0'.$index+1:$index+1 }}</th>
        <th style="height: 30px;">{{ $item->matricul }}</th>
        <th style="height: 30px;">{{ strtoupper($item->first) }}</th>
        <th style="height: 30px;">{{ ucwords($item->last) }}</th>
        <th style="height: 30px; text-align: center">{{ $item->genre == 'F' ? 'Feminin':'Masculin' }}</th>
        <th style="height: 30px; text-align: center"></th>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>