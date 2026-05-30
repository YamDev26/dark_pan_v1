<div class="my-2">
  <div class="bg-secondary text-center rounded p-sm-4">
    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered table-hover mb-0">
        <thead>
          <tr class="text-white">
            <th scope="col" class="text-center w-10">N°</th>
            <th scope="col" class="text-center w-25">Libelle</th>
            <th scope="col" class="text-center w-25">Symbole</th>
            <th scope="col" class="text-center w-25">Bilan</th>
            <th scope="col" class="text-center w-15">Coefficient</th>
          </tr>
        </thead>
        <tbody>
          @php $i = 1 @endphp
          @forelse ($data as $item)
            <tr>
              <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
              <td class="text-left pl-3">{{ ucwords($item['matter']) }}</td>
              <td class="text-center">{{ ucwords($item['symbol']) }}</td>
              <td scope="col" class="text-center w-25">{{ ucwords($item['bilan']) }}</td>
              <td class="text-center">{{ $item['value'] }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-2">Data Not Found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>