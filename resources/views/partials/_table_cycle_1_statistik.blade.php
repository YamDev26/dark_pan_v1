<div class="table-responsive">
  <table class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
      <tr class="text-white">
        {{-- <th scope="col" class="text-center"></th> --}}
        <th scope="col" class="text-center">Niveau</th>
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
      @foreach ($cycle1 as $i => $item1)
        <tr>
          {{-- <td class="text-center">{{ $i+1 < 10 ? '0'.$i+1:$i+1 }}</td> --}}
          <td class="text-center">{{ $item1->symbol }}</td>
          <td class="text-center">
            {{ $item1->nbres_g ? ($item1->nbres_g < 10 ? '0'.$item1->nbres_g:$item1->nbres_g):'--' }}
          </td>
          <td class="text-center">
            {{ $item1->nbres_f ? ($item1->nbres_f < 10 ? '0'.$item1->nbres_f:$item1->nbres_f):'--' }}
          </td>
          <td class="text-center">
            {{ $item1->nbres_t ? ($item1->nbres_t < 10 ? '0'.$item1->nbres_t:$item1->nbres_t):'--' }}
          </td>
          <td class="text-center">
            {{ $item1->admis_g ? ($item1->admis_g < 10 ? '0'.$item1->admis_g:$item1->admis_g):'--' }}
          </td>
          <td class="text-center">
            {{ $item1->admis_f ? ($item1->admis_f < 10 ? '0'.$item1->admis_f:$item1->admis_f):'--' }}
          </td>
          <td class="text-center">
            {{ $item1->admis ? ($item1->admis < 10 ? '0'.$item1->admis:$item1->admis):'--' }}
          </td>
          <td class="text-center">{{ $item1->taux_g ? ($item1->taux_g.' %'):'--' }}</td>
          <td class="text-center">{{ $item1->taux_f ? ($item1->taux_f.' %'):'--' }}</td>
          <td class="text-center">{{ $item1->taux_a ? ($item1->taux_a.' %'):'--' }}</td>
        </tr>
      @endforeach
      <tr>
        <td class="text-center">Résultat</td>
        <td class="text-center">
          {{ $result1 ? ($result1->nbres_g < 10 ? '0'.$result1->nbres_g:$result1->nbres_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result1 ? ($result1->nbres_f < 10 ? '0'.$result1->nbres_f:$result1->nbres_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result1 ? ($result1->nbres_t < 10 ? '0'.$result1->nbres_t:$result1->nbres_t):'--' }}
        </td>
        <td class="text-center">
          {{ $result1 ? ($result1->admis_g < 10 ? '0'.$result1->admis_g:$result1->admis_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result1 ? ($result1->admis_f < 10 ? '0'.$result1->admis_f:$result1->admis_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result1 ? ($result1->admis < 10 ? '0'.$result1->admis:$result1->admis):'--' }}
        </td>
        <td class="text-center">{{ $result1 ? ($result1->taux_g.' %'):'--' }}</td>
        <td class="text-center">{{ $result1 ? ($result1->taux_f.' %'):'--' }}</td>
        <td class="text-center">{{ $result1 ? ($result1->taux_a.' %'):'--' }}</td>
      </tr>
    </tbody>
  </table>
</div>