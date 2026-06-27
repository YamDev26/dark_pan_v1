<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bulletin Trimestriel</title>
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
    }

    /* ===========================
    ENTETE
    =========================== */

    .header{
        display:flex;
        align-items:flex-start;
        border-top: 1px dashed #d6d3d3;
        padding-top: 12px;
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
        font-size:18px;
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
        bottom: -40px;
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
<body>
    <div class="watermark">Bulletin Trimestre 1</div>
    <div class="bulletin">

        <!-- ENTETE -->
        <div class="header">
            <table style="width: 100%">
                <tr>
                    <td class="bloc-gauche">
                        <div class="republique">
                            <span style="font-size: 13px; font-weight:bold; line-height:1.3; padding-bottom: 4px">
                                REPUBLIQUE DE COTE D'IVOIRE<br>
                            </span>
                            <span style="font-size: 10px; line-height: 1.2;">
                                MINISTERE DE L'EDUCATION NATIONALE DE <br>
                                L'ALPHABETISATION ET DE L'ENSEIGNEMENT TECHNIQUE<br>
                            </span>
                        </div>
                    </td>

                    <td class="bloc-centre">
                        <div class="titre">
                            BULLETIN DE NOTES SCOLAIRES
                            <div class="trimestre">
                                {{ getLibelleCutting($cutting->cutting->libelle) }}
                            </div>
                        </div>
                    </td>

                    <td class="bloc-droite">
                        <div style="font-size: 13px">
                            Union - Discipline - Travail
                        </div>
                        <div>
                            Année scolaire<br>
                            <strong>{{ $cutting->school_year->libelle }}</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ETABLISSEMENT -->
        <hr style="width:80%; text-align:center; border: 1px dashed #d6d3d3">
        <table class="table-etablissement">
            <tr>
                <td rowspan="2" class="logo" style="padding:0%">
                    <img src="{{ public_path('storage/'. $school->logo) }}" style="width: 80px; height: 80px; margin: 0%">
                </td>
                <td rowspan="2" class="etablissement" style="text-align: center">
                    <div style="margin: 0px">
                        <strong style="font-size: 18px; line-height: 1.8">{{ strtoupper($school->name) }}</strong>
                    </div>
                    <div class="school-contact" style="margin: 0px; font-size: 13px">
                        {{ $school->email ?? 'Adresse email' }} • {{ $school->addres ?? 'Adresse postal' }} • Tél : {{ $school->phon }}
                    </div>
                    <span style="color: #d6d3d3">--------------------</span>
                    <div style="margin: 0px; font-size: 13px">
                        Code : <strong>{{ $school->code }}</strong> • Statut : <strong>{{ ucwords($school->etat) }}</strong>
                    </div>
                    <span style="color: #d6d3d3">--------------------</span>
                </td>
                <td rowspan="2" class="logo">
                    <img src="{{ $qrCode }}" style="width: 80px; height: 80px; margin: 0%">
                </td>
            </tr>
        </table>
        {{-- <hr style="width:20%; text-align:center; border: 1px dashed #d6d3d3; margin-top: 5px"> --}}
        
        <!-- IDENTITE -->
        <table class="table-identite" style="margin-top: 20px;">

            <tr>
                <td class="infos-eleve">
                    <div class="nom">
                        {{ strtoupper($student->first.' '.$student->last) }} <br>
                        • <strong style="font-size: 15px; font-weight:normal; letter-spacing: 0.1px;">{{ $student->matricul }}</strong> •
                    </div>

                    <table style="width: 100%; margin:0%; padding-left: 12px; font-size: 13px">
                        <tr>
                            <td>Genre : <strong>{{ $student->genre == 'F' ? 'Feminin':'Masculin' }}</strong></td>
                            <td>Classe : <strong>{{ $classe->libelle }}</strong></td>
                            <td>Affecté{{ $student->genre == 'F' ? 'e':'' }} : <strong>{{ $student->affecte ? 'Oui':'Non' }}</strong></td>
                            
                        </tr>
                        <tr>
                            <td>Nationalité : <strong>{{ ucwords($student->libelle) }}</strong></td>
                            <td>Redoublant{{ $student->genre == 'F' ? 'e':'' }} : <strong>{{ $student->redoubant ? 'Oui':'Non' }}</strong></td>
                            <td>Boursi{{ $student->genre == 'F' ? 'ère':'er' }} : <strong>{{ $student->boursier ? 'Oui':'Non' }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                Né{{ $student->genre == 'F' ? 'e':'' }} le <strong>{{ date('d/m/Y', strtotime($student->date)) }}</strong> à <strong>{{ ucwords($student->lieu) }}</strong>
                            </td>
                            <td>Interne : <strong>{{ $student->interne ? 'Oui':'Non' }}</strong></td>
                        </tr>
                    </table>
                </td>

                <td class="photo" style="padding:0%">
                    <img src="{{ public_path('assets/img/user.jpg') }}" alt="" style="width: 80px; height: 80px; margin:0%">
                </td>

            </tr>

        </table>

    </div>

    @yield('content')
    <!-- FOOTER -->
    <div class="footer">
        <span class="footer-text">
        {{ $school->email }} • {{ $school->addres }} •
        {{ $school->phon }} • {{ $classe->libelle }} • 
        {{date('Y-m-d').' ~ N°'.mt_rand(100, 999).'-'.$school->id}}
        </span>
    </div>

</body>
</html>