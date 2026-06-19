<div class="table-responsive">
  <table class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
      <tr class="text-white">
        <th scope="col" class="text-center"></th>
        <th scope="col" class="text-center">Niveau</th>
        <th scope="col" class="text-center">Garçons</th>
        <th scope="col" class="text-center">Filles</th>
        <th scope="col" class="text-center">Effectif</th>
        <th scope="col" class="text-center">Admis G</th>
        <th scope="col" class="text-center">Admis F</th>
        <th scope="col" class="text-center">Ttl Admin</th>
        <th scope="col" class="text-center">Taux G</th>
        <th scope="col" class="text-center">Taux F</th>
        <th scope="col" class="text-center">Pourcentage</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($datas as $i => $data)
        <tr>
          <td class="text-center">{{ $i+1 < 10 ? '0'.$i+1:$i+1 }}</td>
          <td class="text-center">{{ $data->symbol }}</td>
          <td class="text-center">
            {{ $data->effectif_garcon ? ($data->effectif_garcon < 10 ? '0'.$data->effectif_garcon:$data->effectif_garcon):'--' }}
          </td>
          <td class="text-center">
            {{ $data->effectif_fille ? ($data->effectif_fille < 10 ? '0'.$data->effectif_fille:$data->effectif_fille):'--' }}
          </td>
          <td class="text-center">
            {{ $data->effectif_total ? ($data->effectif_total < 10 ? '0'.$data->effectif_total:$data->effectif_total):'--' }}
          </td>
          <td class="text-center">
            {{ $data->admis_garcon ? ($data->admis_garcon < 10 ? '0'.$data->admis_garcon:$data->admis_garcon):'--' }}
          </td>
          <td class="text-center">
            {{ $data->admis_fille ? ($data->admis_fille < 10 ? '0'.$data->admis_fille:$data->admis_fille):'--' }}
          </td>
          <td class="text-center">
            {{ $data->admis ? ($data->admis < 10 ? '0'.$data->admis:$data->admis):'--' }}
          </td>
          <td class="text-center">{{ $data->taux_garcon ? ($data->taux_garcon.' %'):'--' }}</td>
          <td class="text-center">{{ $data->taux_fille ? ($data->taux_fille.' %'):'--' }}</td>
          <td class="text-center">{{ $data->taux_total ? ($data->taux_total.' %'):'--' }}</td>
        </tr>
      @endforeach
      <tr>
          <td colspan="2" class="text-center">Résultat</td>
          <td class="text-center">
            {{ '--' }}
          </td>
          <td class="text-center">
            {{ '--' }}
          </td>
          <td class="text-center">
            {{ '--' }}
          </td>
          <td class="text-center">
            {{ '--' }}
          </td>
          <td class="text-center">
            {{ '--' }}
          </td>
          <td class="text-center">
            {{ '--' }}
          </td>
          <td class="text-center">{{ '--' }}</td>
          <td class="text-center">{{ '--' }}</td>
          <td class="text-center">{{ '--' }}</td>
        </tr>
    </tbody>
  </table>
</div>