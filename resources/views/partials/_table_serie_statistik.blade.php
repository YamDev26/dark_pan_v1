<div class="table-responsive">
  <table class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-white">
          <th colspan="2" scope="col" class="text-center">Niveau</th>
          <th scope="col" class="text-center">Garçon</th>
          <th scope="col" class="text-center">Fille</th>
          <th scope="col" class="text-center">Total</th>
          <th scope="col" class="text-center">Admis G</th>
          <th scope="col" class="text-center">Admis F</th>
          <th scope="col" class="text-center">Admin</th>
          <th scope="col" class="text-center">Taux G</th>
          <th scope="col" class="text-center">Taux F</th>
          <th scope="col" class="text-center">Pourcentage</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($resultats as $niveau)
        @foreach ($niveau['series'] as $index => $serie)
          <tr>
            @if ($index === 0)
              <td rowspan="{{ count($niveau['series']) }}">
                {{ $niveau['niveau'] }}
              </td>
            @endif
            <td class="text-center">{{ $serie['serie'] }}</td>
            <td class="text-center">
              {{ $serie['nbre_g'] ? ($serie['nbre_g'] < 10 ? '0'.$serie['nbre_g']:$serie['nbre_g']):'--' }}
            </td>
            <td class="text-center">
              {{ $serie['nbre_f'] ? ($serie['nbre_f'] < 10 ? '0'.$serie['nbre_f']:$serie['nbre_f']):'--' }}
            </td>
            <td class="text-center">
              {{ $serie['nbre_t'] ? ($serie['nbre_t'] < 10 ? '0'.$serie['nbre_t']:$serie['nbre_t']):'--' }}
            </td>
            <td class="text-center">
              {{ $serie['admis_g'] ? ($serie['admis_g'] < 10 ? '0'.$serie['admis_g']:$serie['admis_g']):'--' }}
            </td>
            <td class="text-center">
              {{ $serie['admis_f'] ? ($serie['admis_f'] < 10 ? '0'.$serie['admis_f']:$serie['admis_f']):'--' }}
            </td>
            <td class="text-center">
              {{ $serie['admis'] ? ($serie['admis'] < 10 ? '0'.$serie['admis']:$serie['admis']):'--' }}
            </td>
            <td class="text-center">{{ $serie['taux_g'] ? ($serie['taux_g'].' %'):'--' }}</td>
            <td class="text-center">{{ $serie['taux_f'] ? ($serie['taux_f'].' %'):'--' }}</td>
            <td class="text-center">{{ $serie['taux_a'] ? ($serie['taux_a'].' %'):'--' }}</td>
          </tr>
        @endforeach
      @endforeach
      <tr>
        <td colspan="2" class="text-center">Résultat</td>
        <td class="text-center">
          {{ $result ? ($result->nbres_g < 10 ? '0'.$result->nbres_g:$result->nbres_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result ? ($result->nbres_f < 10 ? '0'.$result->nbres_f:$result->nbres_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result ? ($result->nbres_t < 10 ? '0'.$result->nbres_t:$result->nbres_t):'--' }}
        </td>
        <td class="text-center">
          {{ $result ? ($result->admis_g < 10 ? '0'.$result->admis_g:$result->admis_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result ? ($result->admis_f < 10 ? '0'.$result->admis_f:$result->admis_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result ? ($result->admis < 10 ? '0'.$result->admis:$result->admis):'--' }}
        </td>
        <td class="text-center">{{ $result ? ($result->taux_g.' %'):'--' }}</td>
        <td class="text-center">{{ $result ? ($result->taux_f.' %'):'--' }}</td>
        <td class="text-center">{{ $result ? ($result->taux_a.' %'):'--' }}</td>
      </tr>
    </tbody>
</table>
</div>