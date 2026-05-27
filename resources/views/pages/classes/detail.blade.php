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
            <button type="button" id="addNew" class="btn btn-outline-primary py-1 mx-2" data-id="{{ $level['id'] }}">Nouvelle</button>
            <a href="{{ route('classe.index') }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-4">
            <div class="table-responsive">
              <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                  <tr class="text-white">
                    <th scope="col" class="text-center w-15">N°</th>
                    <th scope="col" class="text-center w-25">Libelle</th>
                    <th scope="col" class="text-center w-15">Effectif</th>
                    <th scope="col" class="text-center w-15">Status</th>
                    <th scope="col" class="text-center w-25">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @php $i = 1 @endphp
                  @forelse ($data as $item)
                    <tr>
                      <td class="text-center">{{ $i < 10 ? '0'.$i++:$i++ }}</td>
                      <td class="text-left px-5" title="{{ $item['serie'] ? 'Série '.$item['serie']['libelle']:'' }}">
                        <div class="text-left ml-">
                          {{ ucwords($item['libelle']).' '.ucwords($item['lv2'] ? '~ '.$item['lv2']:'')}}
                        </div>
                      </td>
                      <td class="text-center">
                        {{ ($item['inscrit'] < 10 ? '0'.$item['inscrit']:$item['inscrit']).' / '.$item['effectif'] }}
                      </td>
                      <td class="text-center">
                        <span style="border-bottom: 2px solid {{ $item['status'] ? 'green':'red' }}">{{ $item['status'] ? 'Actif':'Inactif' }}</span>
                      </td>
                      <td class="text-center py-2">
                        <a href="{{ route('classe.list', $item['id']) }}" class="btn btn-sm btn-info text-white py-1">Detail</a>
                        <button data-id="{{ $item['id'] }}"  class="btn btn-sm btn-warning text-white BtnEdit py-1 mx-2">Edit</button>
                        <button data-id="{{ $item['id'] }}" class="btn btn-sm btn-primary text-white py-1 btnDelte">Delete</button>
                      </td>
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
<!-- Modal Add New -->
<div class="modal" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('classe.store', $level['id']) }}" method="post">
        @csrf
        <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">Nouvelle Classe</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-2">
          @if($series)
          <div class="form-group mb-2" id="serieDiv" style="display: {{ $series ? '':'none' }}">
            <label for="serie" class="col-form-label">Serie<span class="text-danger">*</span> :</label>
            <select name="{{ $series ? 'serie':'' }}" class="form-select mb-3" id="serie" data-id="{{ $level['id'] }}">
              <option value="">Select ...</option>
              @foreach($series as $item)
                <option value="{{ $item['id'] }}">{{ ucwords($item['libelle']) }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="form-group mb-3" id="lv2Div" style="display: none">
            <label class="col-form-label">Langue Vivante 2 (LV2)<span class="text-danger">*</span> :</label> <br>
            <div class="form-check form-check-inline" title="Allemand">
              <input type="radio" name="lv2" class="form-check-input" id="all" value="all">
              <label class="form-check-label" for="all">Allemand</label>
            </div>
            <div class="form-check form-check-inline" title="Espagnol">
              <input type="radio" name="lv2" class="form-check-input" id="esp" value="esp">
              <label class="form-check-label" for="esp">Espagnol</label>
            </div>
            <div class="form-check form-check-inline" title="Mixte">
              <input type="radio" name="lv2" class="form-check-input" id="mix" value="mix">
              <label class="form-check-label" for="mix">Mixte</label>
            </div>
          </div>
          <div class="form-group mb-3">
            <div class="row">
              <div class="col-6">
                <label for="number" class="col-form-label">Effectif Classe<span class="text-danger">*</span> :</label>
                <input type="text" name="number" class="form-control number" id="number" data-id="number" value="30">
              </div>
              <div class="col-6">
                <label for="nbre" class="col-form-label">Nombre Classes<span class="text-danger">*</span> :</label>
                <input type="text" name="nbre" class="form-control nbre" id="nbre" data-id="nbre" value="1">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer mb-0">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Edit -->
<div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('classe.update', $level['id']) }}" method="post">
        @csrf
        <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">Edit Classe</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-2">
          <h4 class="text-center mt-2">
            <span id="libs"></span>
            <span id="lib"></span>
          </h4>
          <div class="form-group mb-3" id="divLv2" style="display: none">
            <label class="col-form-label">Langue Vivante 2 (LV2)<span class="text-danger">*</span> :</label> <br>
            <div class="form-check form-check-inline" title="Allemand">
              <input type="radio" name="lv2" class="form-check-input all" id="all2" value="all">
              <label class="form-check-label" for="all2">Allemand</label>
            </div>
            <div class="form-check form-check-inline" title="Espagnol">
              <input type="radio" name="lv2" class="form-check-input esp" id="esp2" value="esp">
              <label class="form-check-label" for="esp2">Espagnol</label>
            </div>
            <div class="form-check form-check-inline" title="Mixte">
              <input type="radio" name="lv2" class="form-check-input mix" id="mix2" value="mix">
              <label class="form-check-label" for="mix2">Mixte</label>
            </div>
            <input type="hidden" name="id" id="editId">
          </div>
          <div class="form-group mb-3">
            <div class="row">
              <div class="col-7">
                <label for="effect" class="col-form-label">Effectif Classe<span class="text-danger">*</span> :</label>
                <input type="text" name="number" class="form-control number" id="effect" data-id="effectif" value="30">
              </div>
              <div class="col-5 pt-4 pl-3">
                <div class="mt-3" title="Status">
                  <input type="checkbox" name="status" class="form-check-input" id="sts">
                  <label class="form-check-label mx-2" for="status" id="label"></label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer mb-0">
          <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
          <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Edit -->
<div class="modal" id="delteModal" tabindex="-1" aria-labelledby="delteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content pb-0" style="background: #191C24">
      <form action="{{ route('classe.delete',$level['id']) }}" method="post">
        @csrf
        <div class="modal-header py-2">
          <h5 class="modal-title" id="myModalLabel">Delete Classe</h5>
          <h3><i class="fa fa-user-edit text-primary"></i></h3>
        </div>
        <div class="modal-body py-2">
          <h4 class="text-center mt-2">
            <span id="libs1"></span>
            <span id="lib1"></span>
          </h4>
          <div class="text-center">
            Cette action peut avoir des modifictions majeurs !
            <p>Cliquez sur 'VALIDER' pour continuez.</p>
            <i class="fa fa-exclamation-circle text-danger" style="font-size: 30px"></i>
          </div>
        </div>
        <input type="hidden" name="id" id="delete">
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

    // Uniquement que les chiffres ----
    $(document).on('input', '.number, .nbre', function() {
      $(this).data('id') == 'nbre' ?
      this.value = this.value.replace(/[^1-3]/g, '').substring(0, 1):
      this.value = this.value.replace(/\D/g, '').substring(0, 2);
    });


    $('#addNew').on('click', function() {
      const id = $(this).data('id');
      if([3, 4].includes(Number(id))) {
        getDivLv2();
      }
      $('#addModal').modal('show');
    });


    $(document).on('change', '#serie', function() {
      const id = Number($(this).val());
      const lvl = $(this).data('id');
      const show = [2, 3].includes(id) || lvl == 5;
      $('#lv2Div').toggle(show, 300);
      if(!show) {
        $('#all').prop('checked', false);
      }
      if(show) {
        getDivLv2();
      }
    });

    
    $(document).on('click', '.BtnEdit', function() {
      $id = $(this).data('id');
      if($id) {
        $('#editId').val($id);
        ajax($id, function(data) {
          $('#libs').text(data['lib']);
          $('#effect').text(data['eff']);
          $('#sts').prop('checked', data['status'] == 1 ? true:false);
          $('#label').text('Status '+ (data['status'] ? '(Actif)':'(Inactif)'));
          data['lv2'] ? $('#divLv2').show(300):$('#divLv2').hide();
          data['lv2'] ? $('.'+data['lv2']).prop('checked', true):null;
          $('#lib').text(
            (['A1', 'A2'].includes(data['serie'])) ? 
            ' - Série '+data['serie']:''
          );
          $("#editModal").modal("show");
        });
      }
    });


    $(document).on('click', '.btnDelte', function() {
      $('#delete').val($(this).data('id'));
      ajax($(this).data('id'), function(data) {
        $('#libs1').text(data['lib']);
        $('#lib1').text(
          (['A1', 'A2'].includes(data['serie'])) ? 
          ' - Série '+data['serie']:''
        );
        $("#delteModal").modal("show");
      });
    });


    // Function
    function getDivLv2() {
      $('#lv2Div').show(300);
      $('#all').prop('checked', true);
    }

    function ajax(id, callback) {
      $.ajax({
        url: '{{ route('classe.edit') }}',
        method: 'GET',
        data: {
          id: id
        },
        success: function(data){
          callback(data);
        }
      })
    }

  });
</script>
@endsection