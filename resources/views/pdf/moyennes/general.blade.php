```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Moyenne {{ $classe['libelle'].' '.ucwords($cutting['cutting']['libelle']) }}</title>
    <style>
      @page {
        margin: 50px 30px 50px 30px;
      }

      body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
      }

      header {
        /* position: fixed; */
        top: -110px;
        left: 0;
        right: 0;
        height: 80px;
        text-align: center;
      }

      footer {
        position: fixed;
        bottom: -50px;
        left: 0;
        right: 0;
        height: 40px;
        text-align: center;
        font-size: 10px;
      }

      .footer-page:after {
        content: counter(page);
      }

      .title {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
      }

      .info {
        margin: 20px 0;
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      table th,
      table td {
        border: 1px solid #6C7293;
        padding: 6px;
      }

      table th {
        text-align: center;
        font-weight: bold;
      }

      .text-center {
        text-align: center;
      }
    </style>
</head>

<body>

<header>
    <h3>RÉPUBLIQUE DE CÔTE D'IVOIRE</h3>
    <div>COLLÈGE EXEMPLE</div>

    <div class="title">
      BULLETIN DE NOTES
    </div>
</header>

<footer>
    Édité le {{ now()->format('d/m/Y H:i') }}
    -
    Page <span class="footer-page"></span>
</footer>

<main style="margin-top: 10px">
    <table>
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
                <th>{{ $i }}</th>
              </tr>
            @endforeach
        </tbody>
    </table>

</main>

</body>
</html>
```
