@extends('app')
@section('title', 'List Levels')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Detail Level</h4>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-4">
            <div class="table-responsive">
              <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                  <tr class="text-white">
                    <th scope="col" class="text-center">N°</th>
                    <th scope="col" class="text-center">Libelle</th>
                    <th scope="col" class="text-center">Symbole</th>
                    <th scope="col" class="text-center">Classe</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php $i = 1 @endphp
                  @forelse ($data as $item)
                    <tr>
                      <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
                      <td class="text-center">{{ ucwords($item['libelle']) }}</td>
                      <td class="text-center">{{ ucwords($item['symbol']) }}</td>
                      <td class="text-center">{{ $item->get_classe() }}</td>
                      <td class="text-center">
                        <a href="{{ route('classe.show', $item['id']) }}" class="btn btn-sm btn-warning text-white py-1">Detail</a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center py-2">Data Not Found</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection