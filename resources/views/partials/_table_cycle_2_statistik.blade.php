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
      @foreach ($cycle2 as $niveau)
        @foreach ($niveau['series'] as $index => $serie)
          <tr>
            @if ($index === 0)
              <td rowspan="{{ count($niveau['series']) }}">
                {{ $niveau['niveau'] }}
              </td>
            @endif
            <td class="text-center">{{ $serie['serie'] }}</td>
            <td class="text-center">
              {{ $serie['nbres_g'] ? ($serie['nbres_g'] < 10 ? '0'.$serie['nbres_g']:$serie['nbres_g']):'--' }}
            </td>
            <td class="text-center">
              {{ $serie['nbres_f'] ? ($serie['nbres_f'] < 10 ? '0'.$serie['nbres_f']:$serie['nbres_f']):'--' }}
            </td>
            <td class="text-center">
              {{ $serie['nbres_t'] ? ($serie['nbres_t'] < 10 ? '0'.$serie['nbres_t']:$serie['nbres_t']):'--' }}
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
          {{ $result2 ? ($result2->nbres_g < 10 ? '0'.$result2->nbres_g:$result2->nbres_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result2 ? ($result2->nbres_f < 10 ? '0'.$result2->nbres_f:$result2->nbres_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result2 ? ($result2->nbres_t < 10 ? '0'.$result2->nbres_t:$result2->nbres_t):'--' }}
        </td>
        <td class="text-center">
          {{ $result2 ? ($result2->admis_g < 10 ? '0'.$result2->admis_g:$result2->admis_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result2 ? ($result2->admis_f < 10 ? '0'.$result2->admis_f:$result2->admis_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result2 ? ($result2->admis < 10 ? '0'.$result2->admis:$result2->admis):'--' }}
        </td>
        <td class="text-center">{{ $result2 ? ($result2->taux_g.' %'):'--' }}</td>
        <td class="text-center">{{ $result2 ? ($result2->taux_f.' %'):'--' }}</td>
        <td class="text-center">{{ $result2 ? ($result2->taux_a.' %'):'--' }}</td>
      </tr>
    </tbody>
</table>
</div>