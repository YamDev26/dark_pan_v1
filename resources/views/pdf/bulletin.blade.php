<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bulletin Scolaire</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap');

    @page {
        margin: 30px 10px 20px 10px; /* Supprime toutes les marges */
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

    body{
        font-family: 'Times New Roman', serif;
        font-size:12px;
        background:#fff;
        padding:10px;
    }

    .bulletin{
        width:100%;
        max-width:1200px;
        margin:auto;
        vertical-align:middle;
    }

    /* ===========================
    ENTETE
    =========================== */

    .header{
        display:flex;
        align-items:flex-start;
        border-top: 1px dashed #d6d3d3;
        padding-top: 0px;
        padding-bottom: 5px;
    }

    .bloc-gauche{
        width: 35%;
        text-align:center;
        padding:10px;
    }

    .bloc-centre{
        width: 30%;
        display:flex;
        justify-content:center;
        align-items:center;
        padding-top:10px;
    }

    .titre{
        width:240px;
        border: 1px solid #000;
        text-align:center;
        font-size:14px;
        padding:8px;
    }

    .trimestre{
        font-size:16px;
        font-weight:bold;
    }

    .bloc-droite{
        width: 35%;
        text-align:center;
        padding-top:10px;
        font-size:14px;
    }

    /* ===========================
    ETABLISSEMENT
    =========================== */

    .table-etablissement{
        width:100%;
        border-collapse:collapse;
    }

    .table-etablissement td{
        vertical-align:middle;
    }

    .logo{
        width:90px;
        text-align:center;
        padding: 0px
    }

    .etablissement{
        width:auto;
    }

    .code{
        width:260px;
        line-height:1.8;
    }

    /* ===========================
    IDENTITE ELEVE
    =========================== */

    .table-identite{
        width:100%;
        border: 0.1px solid #000;
        border-collapse:collapse;
    }

    .table-identite td{
        vertical-align:top;
    }
    .nom{
        padding-top: 5px;
        font-size: 16px;
        font-weight: bold;
        line-height: 1.1;
        text-align: center
    }

    .photo{
        width:90px;
        padding: 0%;
        text-align:center;
        vertical-align:middle !important;
    }
    .footer {
        position: fixed;
        bottom: -45px;
        border-top: 1px solid gray;
        padding-top: 10px;
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
<body style="padding: 0%">

    @yield('content')

</body>
</html>