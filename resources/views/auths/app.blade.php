<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }} | @yield('title')</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
  <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

  <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(2, 2, 2, 0.6)), url('assets/img/auth.jpg');
      background-size: cover;        /* L'image couvre tout le bloc */
      background-position: center;   /* Centre l'image */
      background-repeat: no-repeat;  /* Pas de répétition */
      min-height: 100vh;
    }
  </style>

</head>
<body>
  <div class="container-fluid position-relative d-flex p-0">
    <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
      <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>


    {{-- <img src="{{ asset('assets/img/auth_bg.jpg') }}" alt="" srcset=""> --}}

    @yield('content')
    <div class="tect-center" style="position: fixed; width: 100%; text-align: center; bottom: 0px; color: rgb(217, 213, 213); font-style: italic; font-size: 12px">
      Votre réussite, Notre engagement.
    </div>
  </div>

  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script>
    alertify.set('notifier','position', 'top-center');
    alertify.set('notifier','delay', 30);
    @if ($errors->any())
      @foreach ($errors->all() as $error)
        alertify.error("{{ $error }}");
      @endforeach
    @endif
</script>
</body>
</html>