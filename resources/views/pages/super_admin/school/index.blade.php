@extends('app')
@section('title', 'Create School')
@section('content')
<div class="container-fluid pt-2">
  <div class="row g-4">
    <div class="col-12">
      <div class="container-fluid pt-4">
        <div class="bg-secondary text-center rounded p-4">
          <div class="d-flex align-items-center justify-content-between mb-0">
            <h4 class="mb-0">List School</h4>
            <a href="{{ route('school.create') }}" title="New school" style="font-size: 19px">Add</a>
          </div>
          <hr class="mb-4" style="border: 1px solid">
          <div class="table-responsive mt-2">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
              <thead>
                <tr class="text-white">
                  <th scope="col" style="width: 5%"></th>
                  <th scope="col" class="text-center" style="width: 25%">Libelle School</th>
                  <th scope="col" class="text-center" style="width: 20%">Code School</th>
                  <th scope="col" class="text-center" style="width: 25%">Autorisation</th>
                  <th scope="col" class="text-center" style="width: 12.5%">Status</th>
                  <th scope="col" class="text-center" style="width: 12.5%">Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i = 1; @endphp
                @forelse ($data as $item)
                <tr>
                  <td class="text-center">{{ $i< 10 ? '0'.$i++:$i++ }}</td>
                  <td>{{ ucwords($item['name_school']) }}</td>
                  <td>{{ $item['code'] }}</td>
                  <td>{{ $item['autorisation'] }}</td>
                  <td class="text-center">
                    <span style="border-bottom: 1px solid {{ $item['status'] ? 'green':'red' }}">
                      {{ $item['status'] ? 'Actif':'Inactif' }}
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('school.edit', $item['id']) }}" class="btn btn-sm btn-warning text-white py-1" title="Edit Info">
                      Edit
                    </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="py-2 text-center">Data Not Found</td>
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
@endsection
@section('script')
<script>
  $(document).ready(function() {
    
  })
</script>
@endsection