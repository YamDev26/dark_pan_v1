@extends('app')
@section('title', 'List Dren')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Liste Dren</h4>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-4">
            <div class="table-responsive">
              <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                  <tr class="text-white">
                    <th scope="col" class="text-center"></th>
                    <th scope="col" class="text-center">Libellé</th>
                    <th scope="col" class="text-center">Email</th>
                    {{-- <th scope="col" class="text-center">Status</th> --}}
                  </tr>
                </thead>
                <tbody>
                  @php $i = 1 @endphp
                  @forelse ($data as $item)
                    <tr>
                      <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
                      <td class="text-left">{{ ucwords($item['libelle']) }}</td>
                      <td class="text-lft">{{ $item['email'] }}</td>
                      {{-- <td class="text-center">{{ $item['status'] }}</td> --}}
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
          <div class="my-0 mx-4" style="float: right">
            {{ $data->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection