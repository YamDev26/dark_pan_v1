<style>
  .bulletin_footer{
    width:100%;
    border-collapse:collapse;
    border:1px solid #000;
    font-size:14px;
  }

  .bulletin_footer th,
  .bulletin_footer td{
    border:1px solid #000;
    padding:4px;
    vertical-align:top;
  }

  .bulletin_footer th{
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
    font-weight:bold;
  }

  .checkbox-item{
    display:flex;
    align-items:center;
    gap:8px;
    white-space:nowrap;
    font-size:13px;
  }

  .box{
    font-family: DejaVu Sans, sans-serif;
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
    top:-10px;
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
</style>

<table class="bulletin_footer">
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
      <table class="inner">
        <tr>
          <td class="checkbox-item">
            <span class="box"></span>
            Tableau d'honneur
          </td>
        </tr>
        <tr>
          <td class="checkbox-item checked">
            <span class="box"></span>
            Tableau d'honneur + Encouragement
          </td>
        </tr>
        <tr>
          <td class="checkbox-item">
            <span class="box"></span>
            Tableau d'honneur + Félicitations
          </td>
        </tr>
      </table>
    </td>

    <!-- SANCTIONS -->
    <td style="padding: 5px">
      <table class="inner" style="width: 100%; margin: 0px;">
        <tr>
          <td class="checkbox-item" style="padding: 2px 2px">
            <span class="box"></span>
            Avertissement pour travail insuffisant
          </td>
        </tr>
        <tr>
          <td class="checkbox-item checked" style="padding: 2px 2px">
            <span class="box"></span>
            Blâme pour Travail insuffisant
          </td>
        </tr>
        <tr>
          <td class="checkbox-item" style="padding: 2px 2px">
            <span class="box"></span>
            Avertissement pour mauvaise Conduite
          </td>
        </tr>
        <tr>
          <td class="checkbox-item" style="padding: 2px 2px">
            <span class="box"></span>
            Blâme pour mauvaise Conduite
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr style="width: 100%">
    <th style="width: 30%">Rappel</th>
    <th style="width: 35%">Décision du conseil de classe</th>
    <th style="width: 35%">Visa du chef d'établissement</th>
  </tr>

  <tr>
    <td style="vertical-align: middle; text-align:center; padding:0%">
      <table class="inner">
        <tr>
          <td>Moy. 1e Trim : <strong>12.30</strong></td>
          <td><strong>8eme</strong>/15</td>
        </tr>
        <tr>
          <td>Moy. 2e Trim : <strong>12.30</strong></td>
          <td><strong>8eme</strong>/15</td>
        </tr>
        <tr>
          <td>Moy. 3e Trim : <strong>12.30</strong></td>
          <td><strong>8eme</strong>/15</td>
        </tr>
      </table>
    </td>
    <!-- APPRECIATION -->
    <td class="center">
      <div class="appreciation">
        <strong>Admin en supérieur <br> 4eme</strong>
        <br>
        Le Professeur Principal.
        <br><br>
        <strong>M. YAPO THEODORE BROCHO</strong>
      </div>
    </td>

    <!-- VISA -->
    <td class="center">
      <div class="appreciation">
        {{ strtoupper($school->city) }}, le 
        <strong>14/05/2025</strong>
        <br>
        <div>Le Directeur des Etudes</div>
        <br><br>
        <strong>M. KOSSONOU Kouassi Yeboua</strong>
      </div>
    </td>
  </tr>
</table>