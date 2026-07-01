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