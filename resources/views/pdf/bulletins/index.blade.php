<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin scolaire</title>
    <link rel="stylesheet" href="style.css">
</head>

<style>
  .resume{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    font-family:"Times New Roman", serif;
    font-size:14px;
}

.resume th,
.resume td{
    border:1px solid #444;
}

.titre th{
    background:#f2f2f2;
    text-align:center;
    font-size:17px;
    font-weight:bold;
    padding:7px;
}

.bloc{
    vertical-align:top;
    padding:10px 12px;
    height:125px;
}

.notes{
    width:100%;
    border-collapse:collapse;
}

.notes td{
    border:none;
    padding:6px 0;
}

.notes td:nth-child(2){
    width:15px;
    text-align:center;
}

.valeur{
    text-align:right;
    font-weight:bold;
    white-space:nowrap;
}

.check{
    margin:5px 0;
    white-space:nowrap;
}

.case{
    display:inline-block;
    width:14px;
    height:14px;
    border:1px solid #444;
    text-align:center;
    line-height:13px;
    font-size:11px;
    margin-right:6px;
    vertical-align:middle;
}

.active{
    font-weight:bold;
}

.signature{
    height:150px;
    text-align:center;
    vertical-align:top;
    padding:10px;
}

.mention{
    font-style:italic;
    font-weight:bold;
    margin-bottom:8px;
}

.espace{
    height:45px;
}

.espace-mini{
    height:12px;
}

.signature strong{
    font-style:italic;
    font-size:16px;
}
</style>

<body>

<table class="resume">

    <tr class="titre">
        <th>Résultat Trimestriel</th>
        <th>Distinctions</th>
        <th>Sanctions</th>
    </tr>

    <tr>

        <td class="bloc">

            <table class="notes">
                <tr>
                    <td>Plus forte moyenne</td>
                    <td>:</td>
                    <td class="valeur">17,68/20</td>
                </tr>

                <tr>
                    <td>Plus faible moyenne</td>
                    <td>:</td>
                    <td class="valeur">10,24/20</td>
                </tr>

                <tr>
                    <td>Moyenne de classe</td>
                    <td>:</td>
                    <td class="valeur">13,93/20</td>
                </tr>
            </table>

        </td>

        <td class="bloc">

            <div class="check">
                <span class="case"></span>
                Tableau d'honneur
            </div>

            <div class="check">
                <span class="case active">✓</span>
                Tableau d'honneur + Encouragement
            </div>

            <div class="check">
                <span class="case"></span>
                Tableau d'honneur + Félicitations
            </div>

            <div class="check">
                <span class="case"></span>
                Refusé
            </div>

        </td>

        <td class="bloc">

            <div class="check">
                <span class="case"></span>
                Avertissement pour travail insuffisant
            </div>

            <div class="check">
                <span class="case"></span>
                Blâme pour travail insuffisant
            </div>

            <div class="check">
                <span class="case"></span>
                Avertissement pour mauvaise conduite
            </div>

            <div class="check">
                <span class="case"></span>
                Blâme pour mauvaise conduite
            </div>

        </td>

    </tr>

    <tr class="titre">
        <th></th>
        <th>Appréciation du Conseil de Classe</th>
        <th>Visa du Chef d'Établissement</th>
    </tr>

    <tr>

        <td></td>

        <td class="signature">

            <p class="mention">
                Assez bon travail, continuez !
            </p>

            <p>
                Le Professeur Principal
            </p>

            <div class="espace"></div>

            <strong>
                M. YAPO THEODORE BROCHO
            </strong>

        </td>

        <td class="signature">

            <p>
                ABIDJAN, le 14/05/2025
            </p>

            <div class="espace-mini"></div>

            <p>
                Le Directeur des Études
            </p>

            <div class="espace"></div>

            <strong>
                M. KOSSONOU Kouassi Yeboua
            </strong>

        </td>

    </tr>

</table>

</body>
</html>