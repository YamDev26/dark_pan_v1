<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Bulletin</title>
  <link rel="stylesheet" href="style.css">
</head>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap');

  @page {
    margin: 20px 10px 20px 10px; /* Supprime toutes les marges */
  }

  *{
    box-sizing:border-box;
    margin:0;
    padding:0;
  }

  body {
    margin: 0;
    padding: 20px;
    font-family: 'Times New Roman', serif;
  }

.bulletin{
  width:100%;
  border-collapse:collapse;
  border:1px solid #000;
  font-size:14px;
}

.bulletin th,
.bulletin td{
  border:1px solid #000;
  padding:4px;
  vertical-align:top;
}

.bulletin th{
  text-align:center;
  font-size:15px;
  font-weight:bold;
  background:#f7f7f7;
}

.inner{
  width:100%;
  border-collapse:collapse;
}

.inner td{
  border:none;
  padding: 6px 2px;
}

.right{
  /* text-align:right; */
  font-weight:bold;
}

.checkbox-item{
  display:flex;
  align-items:center;
  gap:8px;
  margin:5px 0;
  white-space:nowrap;
  font-size:13px;
}

.box{
  width:12px;
  height:12px;
  border: 1px solid #333;
  display:inline-block;
  position:relative;
  flex-shrink:0;
}

.checked .box::after{
  content:"✓";
  position:absolute;
  left:1px;
  top:-4px;
  font-size:18px;
  font-weight:bold;
}

.right-box{
  margin-left: 15px;
}

.rappel td{
  padding:5px 2px;
  font-size:13px;
}

.center{
  text-align:center;
  vertical-align:middle;
}

.appreciation{
  line-height:1.6;
}

.qrcode{
  width:40px;
  height:40px;
  border:1px solid #000;
  margin:0 auto;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:10px;
  font-weight:bold;
}

em{
  font-style:italic;
}
</style>

<body>
<table class="bulletin">
    <tr>
        <th>Résultat Trimestriel</th>
        <th>Distinctions</th>
        <th>Sanctions</th>
    </tr>

    <tr>
        <!-- RESULTAT -->
        <td>
            <table class="inner">
                <tr>
                    <td>Plus forte moyenne :</td>
                    <td class="right">17,68/20</td>
                </tr>
                <tr>
                    <td>Plus faible Moyenne :</td>
                    <td class="right">10,24/20</td>
                </tr>
                <tr>
                    <td>Moyenne Classe :</td>
                    <td class="right">13,93/20</td>
                </tr>
            </table>
        </td>

        <!-- DISTINCTIONS -->
        <td>
            <div class="checkbox-item">
                <span class="box"></span>
                Tableau d'honneur
                <span class="box right-box"></span>
                Refusé
            </div>

            <div class="checkbox-item checked">
                <span class="box"></span>
                Tableau d'honneur + Encouragement
            </div>

            <div class="checkbox-item">
                <span class="box"></span>
                Tableau d'honneur + Félicitations
            </div>
        </td>

        <!-- SANCTIONS -->
        <td>
            <div class="checkbox-item">
                <span class="box"></span>
                Avertissement pour travail insuffisant
            </div>

            <div class="checkbox-item">
                <span class="box"></span>
                Blâme pour Travail insuffisant
            </div>

            <div class="checkbox-item">
                <span class="box"></span>
                Avertissement pour mauvaise Conduite
            </div>

            <div class="checkbox-item">
                <span class="box"></span>
                Blâme pour mauvaise Conduite
            </div>
        </td>
    </tr>

    <tr>
        <th></th>
        <th>Appréciation du Conseil de Classe</th>
        <th>VISA DU CHEF D'ETABLISSEMENT</th>
    </tr>

    <tr>
      <td></td>
      <!-- APPRECIATION -->
      <td class="center">
        <div class="appreciation">
          <strong><em>Assez bon travail, continuez !</em></strong>
          <br>
          Le Professeur Principal.
          <br><br>
          <strong><em>M. YAPO THEODORE BROCHO</em></strong>
        </div>
      </td>

      <!-- VISA -->
      <td class="center">
          <div>ABIDJAN, le 14/05/2025</div>
          <br>
          <div>Le Directeur des Etudes</div>
          <br><br>
          <strong><em>M. KOSSONOU Kouassi Yeboua</em></strong>
      </td>
    </tr>
</table>

</body>
</html>