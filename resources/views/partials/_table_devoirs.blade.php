<div class="my-2">
  <div class="bg-secondary text-center rounded p-sm-4">
    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered table-hover mb-0">
        <thead>
          <tr class="text-white">
            <th scope="col" class="text-center" style="width: 5%">N°</th>
            <th scope="col" class="text-center" style="width: 25%">Libelle</th>
            <th scope="col" class="text-center" style="width: 15%">Classe</th>
            <th scope="col" class="text-center" style="width: 15%">Date</th>
            <th scope="col" class="text-center" style="width: 10%">Heure</th>
            <th scope="col" class="text-center" style="width: 10%">Durée</th>
            <th scope="col" class="text-center" style="width: 10%">Status</th>
            <th scope="col" class="text-center" style="width: 10%">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($data as $i => $item)
            <tr>
              <td class="text-center">{{ sprintf('%02d', $i + 1) }}</td>
              <td class="text-left pl-3">{{ ucwords($item['libelle']).' ~ '.$item['symbol'] }}</td>
              <td scope="col" class="text-center">{{ $item['classe'] }}</td>
              <td class="text-center">{{ date('d/m/Y', strtotime($item['date'])) }}</td>
              <td scope="col" class="text-center">{{ $item['debut'] }}</td>
              <td scope="col" class="text-center">{{ $item['times'] }}</td>
              <td scope="col" class="text-center">
                <span style="border-bottom: 2px solid {{ $item['status'] ? 'green':'red' }}">{{ $item['status'] ? 'Actif':'Inactif' }}</span>
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-light dropdown-toggle py-0" type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="min-width: 6rem;">
                  <li><a href="#" data-id="{{ $item['id'] }}" class="dropdown-item edit">Edit</a></li>
                  <li><a href="#" class="dropdown-item delete" data-id="{{ $item['id'] }}">Delete</a></li>
                </ul>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-2">Data Not Found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>