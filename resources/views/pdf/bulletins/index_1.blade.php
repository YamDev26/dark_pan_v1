<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Bulletin de Notes</title>

<style>

*{
  box-sizing:border-box;
  margin:0;
  padding:0;
}

body{
  font-family: Arial, Helvetica, sans-serif;
  font-size:12px;
  color:#000;
  padding:6px;
}

table{
  width:100%;
  border-collapse:collapse;
}

.border{
  border:0.5px solid #000;
}

td,th{
  vertical-align:top;
}

.text-center{
  text-align:center;
}

.text-right{
  text-align:right;
}

.bold{
  font-weight:bold;
}

.header-table td{
  padding:5px;
}

.logo{
  width:85px;
  text-align:center;
}

.logo img{
  width:70px;
  height:auto;
}

.school-info{
  text-align:center;
  line-height:1.3;
}

.school-name{
  font-size:17px;
  font-weight:600;
  margin-top:5px;
}

.contact-table td{
  padding:4px 8px;
  font-size:13px;
}

.bulletin-title{
    margin-top:8px;
    margin-bottom:8px;
    text-align: center;
    padding-bottom:3px;
    font-size: 17px;
    font-weight:bold;
}

.bulletin-title span{
    /* font-style:italic; */
}

.student-header{
    background:#e6e6e6;
    font-size:18px;
    font-weight:bold;
}

.student-header td{
    padding:4px 8px;
}

.student-info td{
    padding:6px 8px;
}

.label{
    font-weight:bold;
}

.big-value{
    font-size:18px;
    font-weight:bold;
}

.watermark{
    position:relative;
    overflow:hidden;
}

.watermark::after{
    content:"";
    position:absolute;
    right:30px;
    top:0;
    width:150px;
    height:150px;
    opacity:.10;
    background-size:contain;
    background-repeat:no-repeat;
}

</style>
</head>
<body>

<!-- EN-TETE -->
<table class="header-table">
    <tr>

        <!-- LOGO -->
        <td width="12%" class="border logo">
            <img src="logo.png" alt="Logo">
        </td>

        <!-- ECOLE -->
        <td width="40%" class="border school-info">
            <div class="bold">REPUBLIQUE DE COTE D'IVOIRE</div>

            MINISTERE DE L'EDUCATION NATIONALE DE<br>
            L'ALPHABETISATION ET DE L'ENSEIGNEMENT TECHNIQUE

            <div class="school-name">
                COURS SECONDAIRE LA ROCHELLE
            </div>
        </td>

        <!-- CONTACT -->
        <td width="48%" class="border">
            <table class="contact-table">
                <tr>
                  <td><b>Adresse :</b> 10 BP1041 ABIDJAN 10</td>
                  <td class="text-right"><b>Code :</b> 025014</td>
                </tr>

                <tr>
                    <td><b>Téléphone :</b> 27 21 56 48 56</td>
                    <td class="text-right"><b>Statut :</b> Privé</td>
                </tr>

                <tr>
                    <td colspan="2">
                        <b>E-mail :</b>
                        courssecondairelarochelle@gmail.com
                    </td>
                </tr>
            </table>
        </td>

    </tr>
</table>

<!-- TITRE -->
<div class="bulletin-title">
    <div style="text-decoration: underline; margin-bottom: 7px">
      BULLETIN DE NOTES
    </div>
    <span>Troisième Trimestre - Année scolaire : 2024-2025</span>
</div>

<!-- BLOC ELEVE -->
<table class="border">

    <tr class="student-header">
        <td width="70%">
            ABOUA CALEB PRINCE JOEL
        </td>

        <td width="30%" class="text-right">
            Matricule : 24 426 232 K
        </td>
    </tr>

    <tr>
        <td colspan="2" class="watermark">

            <table class="student-info">

                <tr>
                    <td width="35%">
                        <span class="label">Classe :</span>
                        <span class="big-value">6èmeA1</span>
                    </td>

                    <td width="25%">
                        <span class="label">Effectif :</span>
                        <span class="big-value">57</span>
                    </td>

                    <td width="20%">
                        <span class="label">Interne :</span>
                        Non
                    </td>

                    <td width="20%"></td>
                </tr>

                <tr>
                    <td>
                        <span class="label">Sexe :</span>
                        Masculin
                    </td>

                    <td>
                        <span class="label">Redoublant(e) :</span>
                        Non
                    </td>

                    <td>
                        <span class="label">Affecté(e) :</span>
                        Oui
                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td>
                        <span class="label">Nationalité :</span>
                        Ivoirienne
                    </td>

                    <td>
                        <span class="label">Régime :</span>
                    </td>

                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="4">
                        <span class="label">Né (e) le :</span>
                        19/08/2013

                        &nbsp;&nbsp; à &nbsp;&nbsp;

                        TREICHVILLE
                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>