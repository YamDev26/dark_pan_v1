<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap');

    @page {
      margin: 20px 10px 20px 10px; /* Supprime toutes les marges */
    }

    body {
      margin: 0;
      padding: 20px;
      font-family: 'Times New Roman', serif;
    }
    .watermark {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-50deg);
      font-size: 100px;
      color: rgba(0, 0, 0, 0.05);
      z-index: -1;
      white-space: nowrap;
      text-decoration: underline;
      pointer-events: none;
    }

    .header {
      margin-bottom: 15px;
      margin-top: 0px;
      padding-top: 5px;
      border-top: 1px dashed #000;
    }

    .header-table {
      width: 100%;
      border-collapse: collapse;
    }

    .header-table td {
      vertical-align: top;
      border: none;
    }

    .logo {
      width: 80px;
    }

    .logo img {
      width: 70px;
      height: auto;
    }

    .school-info {
      text-align: center;
    }

    .country {
      font-size: 11px;
      font-weight: bold;
    }

    .motto {
      font-size: 10px;
      margin-bottom: 5px;
    }

    .school-name {
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .school-contact {
      font-size: 10px;
    }

    .year {
      width: 120px;
      text-align: right;
      font-size: 12px;
    }

    .document-title {
      margin-top: 10px;
      margin-bottom: 15px;
      text-align: center;
      font-size: 18px;
      font-weight: bold;
      padding: 5px;
      text-decoration: underline
    }

    .footer {
      position: fixed;
      bottom: -45px;
      border-top: 1px solid gray;
      padding-top: 3px;
      left: 0;
      right: 0;
      height: 50px;
      text-align: center;
      font-size: 12px;
      color: #000;
    }

    .footer-text {
      display: inline-block;
    }

    .page-number {
      position: absolute;
      right: 0;
      top: 0;
    }
  </style>
  @yield('link')
</head>
<body>
  <div class="watermark"> @yield('fond_page') </div>
  <div class="header">
    <table class="header-table">
      <tr>
        <td class="logo">
          <img src="{{ public_path('storage/'. $school->logo) }}" alt="Logo de l'école">
        </td>

        <td class="school-info">
          <div class="country">REPUBLIQUE DE COTE D'IVOIRE</div>
          <div class="motto">Union - Discipline - Travail</div>

          <div class="school-name">
            {{ $school->name }}
          </div>

          <div class="school-contact">
            {{ $school->addres }} • Tél : {{ $school->phon }}
          </div>
        </td>

        <td class="year">
          Année Scolaire
          <p style="font-size: 14px; padding-top: 3px">
            <strong>@yield('school_year')</strong>
          </p>
        </td>
      </tr>
    </table>
  </div>

  @yield('content')

  <div class="footer">
    <span class="footer-text">
      {{ $school->email }} • {{ $school->addres }} •
      {{ $school->phon }} • {{ $classe->libelle }} • 
      {{date('Y-m-d').' ~ N°'.mt_rand(100, 999).'-'.$school->id}}
    </span>
    @yield('num_page')
  </div>
</body>
</html>