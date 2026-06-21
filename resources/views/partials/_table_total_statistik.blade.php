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
      @foreach ($total as $i => $data)
        <tr>
          {{-- <td class="text-center">{{ $i+1 < 10 ? '0'.$i+1:$i+1 }}</td> --}}
          <td class="text-center">{{ $data->symbol }}</td>
          <td class="text-center">
            {{ $data->nbres_g ? ($data->nbres_g < 10 ? '0'.$data->nbres_g:$data->nbres_g):'--' }}
          </td>
          <td class="text-center">
            {{ $data->nbres_f ? ($data->nbres_f < 10 ? '0'.$data->nbres_f:$data->nbres_f):'--' }}
          </td>
          <td class="text-center">
            {{ $data->nbres_t ? ($data->nbres_t < 10 ? '0'.$data->nbres_t:$data->nbres_t):'--' }}
          </td>
          <td class="text-center">
            {{ $data->admis_g ? ($data->admis_g < 10 ? '0'.$data->admis_g:$data->admis_g):'--' }}
          </td>
          <td class="text-center">
            {{ $data->admis_f ? ($data->admis_f < 10 ? '0'.$data->admis_f:$data->admis_f):'--' }}
          </td>
          <td class="text-center">
            {{ $data->admis ? ($data->admis < 10 ? '0'.$data->admis:$data->admis):'--' }}
          </td>
          <td class="text-center">{{ $data->taux_g ? ($data->taux_g.' %'):'--' }}</td>
          <td class="text-center">{{ $data->taux_f ? ($data->taux_f.' %'):'--' }}</td>
          <td class="text-center">{{ $data->taux_a ? ($data->taux_a.' %'):'--' }}</td>
        </tr>
      @endforeach
      <tr>
        <td class="text-center">Résultat</td>
        <td class="text-center">
          {{ $result3 ? ($result3->nbres_g < 10 ? '0'.$result3->nbres_g:$result3->nbres_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result3 ? ($result3->nbres_f < 10 ? '0'.$result3->nbres_f:$result3->nbres_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result3 ? ($result3->nbres_t < 10 ? '0'.$result3->nbres_t:$result3->nbres_t):'--' }}
        </td>
        <td class="text-center">
          {{ $result3 ? ($result3->admis_g < 10 ? '0'.$result3->admis_g:$result3->admis_g):'--' }}
        </td>
        <td class="text-center">
          {{ $result3 ? ($result3->admis_f < 10 ? '0'.$result3->admis_f:$result3->admis_f):'--' }}
        </td>
        <td class="text-center">
          {{ $result3 ? ($result3->admis < 10 ? '0'.$result3->admis:$result3->admis):'--' }}
        </td>
        <td class="text-center">{{ $result3 ? ($result3->taux_g.' %'):'--' }}</td>
        <td class="text-center">{{ $result3 ? ($result3->taux_f.' %'):'--' }}</td>
        <td class="text-center">{{ $result3 ? ($result3->taux_a.' %'):'--' }}</td>
      </tr>
    </tbody>
  </table>
</div>