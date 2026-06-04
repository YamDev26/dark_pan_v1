<div class="my-2">
  <div class="bg-secondary text-center rounded p-sm-4">
    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered table-hover mb-0">
        <thead>
          <tr class="text-white">
            <th scope="col" class="text-center">N°</th>
            <th scope="col" class="text-center">Libelle</th>
            <th scope="col" class="text-center">Date</th>
            <th scope="col" class="text-center">Value</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @php $i = 1 @endphp
          @forelse ($data as $item)
            <tr>
              <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
              <td class="text-left pl-3">{{ ucwords($item['libelle']) }}</td>
              <td class="text-center">{{ date('d/m/Y', strtotime($item['date'])) }}</td>
              <td scope="col" class="text-center">{{ ucwords($item['value']) }}</td>
              <td scope="col" class="text-center">
                <span style="border-bottom: 2px solid {{ $item['status'] ? 'green':'red' }}">{{ $item['status'] ? 'Actif':'Inactif' }}</span>
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-outline-light dropdown-toggle py-0" type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="min-width: 6rem;">
                  <li><a href="{{ route('note.show', $item['id']) }}" class="dropdown-item">Note</a></li>
                  <li><a href="#" data-id="{{ $item['id'] }}" class="dropdown-item edit">Edit</a></li>
                  <li><a href="#" class="dropdown-item">Delete</a></li>
                </ul>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-2">Data Not Found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>