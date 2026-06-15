<table class="table text-start align-middle table-bordered table-hover mt-3 mb-0">
  <tbody>
    <tr>
      <td class="text-center">01</td>
      <td><i class="fa fa-users"></i> Effectif</td>
      <th class="text-center">
        {{ $result ? ($result->effectif < 10 ? '0'.$result->effectif:$result->effectif):'00' }}
      </th>
    </tr>
    <tr>
      <td class="text-center">02</td>
      <td><i class="fa fa-female"></i> Taux des filles</td>
      <th class="text-center">
        {{ $result ? ($result->taux_f.'%'):'00' }}
      </th>
    </tr>
    <tr>
      <td class="text-center">03</td>
      <td><i class="fa fa-male"></i> Taux des garçons</td>
      <th class="text-center">
        {{ $result ? ($result->taux_m.'%'):'00' }}
      </th>
    </tr>
    <tr>
      <td class="text-center">04</td>
      <td><i class="fa fa-check-square"></i> Taux de réussite</td>
      <th class="text-center">
        {{ $result ? ($result->reussite.'%'):'00' }}
      </th>
    </tr>
    <tr>
      <td class="text-center">05</td>
      <td><i class="fa fa-database"></i> Moyenne de la classe</td>
      <th class="text-center">
        {{ $result ? ($result->moyenne):'00' }}
      </th>
    </tr>
    <tr>
      <td class="text-center">06</td>
      <td><i class="fa fa-plus-square"></i> Moyenne maximale</td>
      <th class="text-center">
        {{ $result ? ($result->max):'00' }}
      </th>
    </tr>
    <tr>
      <td class="text-center">07</td>
      <td><i class="fa fa-minus-square"></i> Moyenne minimale</td>
      <th class="text-center">
        {{ $result ? ($result->min):'00' }}
      </th>
    </tr>
    <tr>
      <td class="text-center">08</td>
      <td><i class="fas fa-exclamation-triangle"></i> Élèves en difficulté</td>
      <th class="text-center">
        {{ $result ? ($result->dificulte < 10 ? '0'.$result->dificulte:$result->dificulte):'00' }}
      </th>
    </tr>
  </tbody>
</table>