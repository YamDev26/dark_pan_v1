<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Moyenne Global {{ $classe['libelle'].' '.$cutting['cutting']['symbol'] }}</title>
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
        @foreach ($matters as $matter)
          <th style="width: 100px; height: 25px; text-align: center">{{ $matter['symbol'] }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach ($datas as $i => $item )
        <tr>
        <th style="height: 30px; text-align: center">{{ $i < 10 ? '0'.$i+1:$i+1 }}</th>
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