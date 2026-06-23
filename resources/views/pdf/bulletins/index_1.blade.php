<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Bulletin Trimestriel</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap');

    @page {
        margin: 20px 10px 20px 10px; /* Supprime toutes les marges */
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
    /* border:1px solid #000; */
}

/* ===========================
   ENTETE
=========================== */

.header{
    display:flex;
    align-items:flex-start;
    border-top: 1px dashed #d6d3d3;
}

.bloc-gauche{
    width:35%;
    text-align:center;
    padding:10px;
}

/* .republique{
    font-size:11px;
    font-weight:bold;
    line-height:1.2;
} */

.bloc-centre{
    width:40%;
    display:flex;
    justify-content:center;
    align-items:center;
    padding-top:10px;
}

.titre{
    border: 1px solid #000;
    width:320px;
    text-align:center;
    font-size:14px;
    padding:8px;
}

.trimestre{
    font-size:18px;
    font-weight:bold;
}

.bloc-droite{
    width:25%;
    text-align:center;
    padding-top:10px;
    font-size:14px;
    font-weight:bold;
}

/* ===========================
   ETABLISSEMENT
=========================== */

.table-etablissement{
    width:100%;
    border-collapse:collapse;
}

.table-etablissement td{
    border:1px solid #000;
    padding:6px;
    vertical-align:middle;
}

.logo{
    width:90px;
    text-align:center;
    font-weight:bold;
}

.etablissement{
    width:auto;
}

.etablissement strong{
    font-size:18px;
}

.ligne-info{
    margin-top:15px;
}

.telephone{
    margin-left:25px;
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
    border-collapse:collapse;
}

.table-identite td{
    border:1px solid #000;
    vertical-align:top;
    padding:10px;
}

.infos-eleve{
    width:38%;
}

.nom{
    font-size:20px;
    font-weight:bold;
    line-height:1.1;
    margin-bottom:10px;
}

.matricule{
    font-size:16px;
    font-weight:bold;
    margin-bottom:15px;
}

.classe{
    margin-top:20px;
}

.infos-centre{
    width:25%;
    line-height:1.5;
}

.infos-droite{
    width:25%;
    line-height:1.5;
}

.photo{
    width:120px;
    text-align:center;
    vertical-align:middle !important;
    font-size:28px;
}
</style>
<div class="bulletin">

    <!-- ENTETE -->
    <div class="header">

        <table style="width: 100%">
            <tr>
                <td class="bloc-gauche">
                    <div class="republique">
                        <span style="font-size: 12px; font-weight:bold; line-height:1.3;">
                            REPUBLIQUE DE CÔTE D'IVOIRE<br>
                        </span>
                        <span style="font-size: 10px; line-height: 1.2;">
                            MINISTERE DE L'EDUCATION NATIONALE DE <br>
                            L'ALPHABETISATION ET DE L'ENSEIGNEMENT TECHNIQUE<br>
                        </span>
                        {{-- DIRECTION REGIONALE AGBOVILLE --}}
                    </div>
    
                </td>

                <td class="bloc-centre">
                    <div class="titre">
                        BULLETIN DE NOTES SCOLAIRES
                        <div class="trimestre">3ème trimestre</div>
                    </div>
                </td>

                <td class="bloc-droite">
                    <div>
                        Année scolaire<br>
                        <strong>2015/2016</strong>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <!-- ETABLISSEMENT -->
    <table class="table-etablissement">
        <tr>
            <td rowspan="2" class="logo">
                LOGO
            </td>

            <td rowspan="2" class="etablissement">
                <div>
                    Etablissement :
                    <strong>LYCEE MODERNE 3 AGBOVILLE</strong>
                </div>

                <div class="ligne-info">
                    Adresse postale :
                    <strong>Adresse postale</strong>

                    <span class="telephone">
                        Téléphone:
                        <strong>01957723</strong>
                    </span>
                </div>
            </td>

            <td class="code">
                Code:
                <strong>000680</strong>
            </td>
        </tr>

        <tr>
            <td class="code">
                statut:
                <strong>Public</strong>
                <br><br>
                E-mail:
                <strong>mdaniellekouame@gmail.com</strong>
            </td>
        </tr>
    </table>

    <!-- IDENTITE -->
    <table class="table-identite">

        <tr>

            <td class="infos-eleve">

                <div class="nom">
                    AYEMENET ARNAUD PAUL<br>
                    VIANNEY
                </div>

                <div class="matricule">
                    Matricule: 08052229D
                </div>

                <div class="classe">
                    Classe : TD1
                </div>

                <div>
                    Bulletin N° 10/47
                </div>

            </td>

            <td class="infos-centre">

                <div>Genre : <strong>M</strong></div>
                <div>Né le <strong>05/11/1997</strong></div>
                <div>Lieu de naissance : <strong>AGBOVILLE</strong></div>
                <div>Nationalité : <strong>IVOIRIENNE</strong></div>

            </td>

            <td class="infos-droite">

                <div>Redoublant : <strong>non</strong></div>
                <div>Boursier : <strong>non</strong></div>
                <div>Interne : <strong>d/p</strong></div>
                <div>Affecté : <strong>oui</strong></div>

            </td>

            <td class="photo">
                PHOTO
            </td>

        </tr>

    </table>

</div>

</body>
</html>