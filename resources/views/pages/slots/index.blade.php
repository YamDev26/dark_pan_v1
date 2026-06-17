@extends('app')
@section('title', 'Slot Time')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded px-4 pt-2 pb-0">
        <div class="d-flex align-items-center justify-content-between mb-0">
          <h4 class="mb-0">Detail Heures</h4>
          @if (count($data['dt1']))
            <h3><i class="fa fa-user-edit text-primary"></i></h3>
          @else
            <a href="{{ route('slot.create') }}" class="btn btn-outline-primary py-0">Add</a>
          @endif
        </div>
        <hr class="mt-0">
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-4">
            <div class="table-responsive">
              <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                  <tr class="text-white">
                    <th scope="col" class="text-center">Libellé</th>
                    <th scope="col" class="text-center">Debut</th>
                    <th scope="col" class="text-center">Fin</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($data['dt1']))
                  @foreach ($data['dt1'] as $item1)
                    <tr>
                      <td class="text-center">{{ ($item1['order'] > 1 ? $item1['order'].'ème':$item1['order'].'ère').' heure' }}</td>
                      <td class="text-center">{{ $item1['dbt'] }}</td>
                      <td class="text-center">{{ $item1['fin'] }}</td>
                      <td class="text-center">
                        <buttom type="buttom" data-id="{{ $item1['id'] }}" class="btn btn-sm btn-warning text-white py-1">Edit</buttom>
                      </td>
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="4" class="text-center py-2">
                      Après midi
                    </td>
                  </tr>
                  @foreach ($data['dt2'] as $item2)
                    <tr>
                      <td class="text-center">{{ ($item2['order'] > 1 ? $item2['order'].'ème':$item2['order'].'ère').' heure' }}</td>
                      <td class="text-center">{{ $item2['dbt'] }}</td>
                      <td class="text-center">{{ $item2['fin'] }}</td>
                      <td class="text-center">
                        <buttom type="buttom" data-id="{{ $item2['id'] }}" class="btn btn-sm btn-warning text-white py-1">Edit</buttom>
                      </td>
                    </tr>
                  @endforeach  
                  @else
                    <tr>
                      <td colspan="4" class="text-center py-2">Data Not Found</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit -->
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('slot.update') }}" method="post">
        @csrf
        <div class="modal-header pt-2 pb-0">
          <h4 class="modal-title" id="myModalLabel">Edit Heure</h4>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-4">
          <h5 id="libelle"></h5>
          <div class="row mt-3">
            <div class="col-6">
              <label for="dbt" class="col-form-label">Heure début : </label>
              <input type="time" name="dbt" class="form-control" id="dbt">
            </div>
            <div class="col-6">
              <label for="fin" class="col-form-label">Heure fin : </label>
              <input type="time" name="fin" class="form-control" id="fin">
            </div>
          </div>
          <input type="hidden" name="id" id="idEdit">
        </div>
        <div class="modal-footer mb-0">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('script')
  <script>
    $(document).ready(function() {
      $(document).on('click', '.btn-warning', function() {
        $id = $(this).data('id');
        if($id) {
          $.ajax({
            url: '{{ route('slot.edit') }}',
            method: 'GET',
            data: {
              id: $id
            },
            success: function(data){
              if(data) {
                $('#dbt').val(data['dbt']);
                $('#fin').val(data['fin']);
                $('#idEdit').val(data['id']);
                $('#libelle').text(
                  data['period'] == 1 ?
                  'Matinnée - '+(data['order'] > 1 ? data['order']+'ème':data['order']+'ère')+' heure':
                  'Après midi - '+(data['order'] > 1 ? data['order']+'ème':data['order']+'ère')+' heure'
                );
              }
              $("#myModal").modal("show"); // Affichage Le la Modal Edit Composition
            }
          })
        }
      })
    })
  </script>
@endsection