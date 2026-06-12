<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Application Gestion Ecole">
    <meta name="author" content="YamDev 26">
    <title>{{ config('app.name') }} | @yield('title')</title>
</head>
<style>
  @page {
    margin: 50px 25px 50px 25px; /* top, right, bottom, left */
  }

  body {
    font-size: .875em;
    overflow-x: hidden;
    color: #353c4e;
    font-family: "Open Sans", sans-serif;
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
    pointer-events: none;
  }

  #header {
    position: fixed;
    border-bottom: 1px solid gray;
    left: 0;
    right: 0;
    top: -40px;
    text-align: center;
    font-size: 10px;
    color: #555;
  }

  #footer {
    position: fixed;
    bottom: -60px;
    border-top: 1px solid gray;
    left: 0;
    right: 0;
    height: 40px;
    text-align: center;
    font-size: 12px;
  }
</style>
<body>
    <div class="watermark">
      @yield('watermark')
    </div>
    
    <header id="header">
      <table style="width: 100%; margin: 0px auto; padding:0%">
        <tbody>
          <tr>
            <td style="width: 50%">
              <table style="width: 100%">
                <tbody>
                  <tr>
                    <td>
                      <div style="text-align: center; font-size: 10px">Ministère de l'Education Nationale, de l'Enseignement <br> Technique et de la   Formation Professionnelle</div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
            <td style="width: 50%">
              <table style="width: 100%">
                <tbody>
                  <tr>
                    <td>
                      <div style="text-align: center; font-size: 10px;">
                        <b>REPUBLIQUEDE COTE D'IVOIRE</b><br>
                        <i>Union - Discipline - Travail</i>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
      <div style="width: 100%; margin: 0px auto; padding:0%; border: 1px solid">
        <table style="width: 100%">
          <tbody>
            <tr>
              <td style="width: 10%; border-right: 1px solid">
                <img src="{{ public_path('storage/'. $school->logo) }}" width="80" height="70">
              </td>
              <td style="width: 80%; text-align:center">
                <h2 style="margin:0%">{{ ucwords($school->name) }}</h2>
                {{ $school->email }}
              </td>
              <td style="width: 10%; border-left: 1px solid">
                <img src="{{ $qrcode }}" width="80" height="70">
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </header>
    
    <div class="content">
      @yield('content')
    </div>
    
    <footer id="footer">
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 50%">
                      <i style="text-align: left; font-size: 12px; margin:0%">{{ ucwords($school->name) }}</i>
                    </td>
                    <td style="width: 25%">
                      <i style="text-align: center; font-size: 12px; margin:0%">{{ $school->email }}</i>
                    </td>
                    <td style="width: 25%">
                      <i style="text-align: right; float:right; font-size: 12px; margin:0%">{{ date('Y').' ~ '.random_int(1000, 5000) }}</i>
                    </td>
                </tr>
            </tbody>
        </table>
    </footer>
</body>
</html>