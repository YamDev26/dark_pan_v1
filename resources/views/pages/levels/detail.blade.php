@extends('app')
@section('title', 'Detail '.$level['symbol'])
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Detail {{ $level['symbol'] }}</h4>
          <div>
            <a href="{{ route('level.create', $level['id']) }}" class="btn btn-outline-primary py-1 mx-2">Edit</a>
            <a href="{{ route('level.index') }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
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
                      <td class="text-left pl-3">{{ ucwords($item->matter->libelle) }}</td>
                      <td class="text-center">{{ ucwords($item->matter->symbol) }}</td>
                      <td scope="col" class="text-center w-25">{{ ucwords($item->matter->bilanMatter->libelle) }}</td>
                      <td class="text-center">{{ $item->value }}</td>
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
      </div>
    </div>
  </div>
</div>
@endsection