@extends('app')
@section('title', 'Matter '.$level['symbol'])
@section('link')
<style>
  .row-disabled{
    background: #dcdcdc;
  }
</style>
@endsection
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Ajout Matières {{ $level['symbol'] . ($serie ? ' - '.$serie['libelle']:'') }}</h4>
          <a href="{{ route('level.show', $level['id']) }}" class="btn btn-outline-light py-1">Return</a>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary text-center rounded p-sm-4">
            <form action="{{ route('level.store', $level['id']).($serie ? '_'.$serie['id']:'') }}" method="post" id="myForm">
              @csrf
              <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                  <thead>
                    <tr class="text-white">
                      <th scope="col" class="text-center"></th>
                      <th scope="col" class="text-center">Libelle</th>
                      <th scope="col" class="text-center">Symbole</th>
                      <th scope="col" class="text-center">Bilan</th>
                      <th scope="col" class="text-center">Coefficient</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($data as $item)
                      <tr id="row{{ $item['id'] }}">
                        <td class="text-center">
                          <input type="checkbox" class="form-check-input checkbox" value="{{ $item['id'] }}" checked>
                        </td>
                        <td class="text-left py-1">
                          <input type="text" class="form-control m-0 input{{ $item['id'] }}" value="{{ ucwords($item['libelle']) }}" style="border-radius: 0px">
                        </td>
                        <td class="text-center py-1">
                          <input type="text" class="form-control m-0 input{{ $item['id'] }}" value="{{ ucwords($item['symbol']) }}" style="border-radius: 0px">
                        </td>
                        <td class="text-center py-1">
                          <input type="text" class="form-control m-0 input{{ $item['id'] }}" value="{{ ucwords($item['bilanMatter']['libelle']) }}" style="border-radius: 0px">
                        </td>
                        <td class="text-center py-1">
                          <input type="hidden" name="matter[]" class="input{{ $item['id'] }}" value="{{ $item['id'] }}">
                          <input type="text" name="nbres[]" class="form-control number m-0 input{{ $item['id'] }}" placeholder="Coefficient" pattern="[1-5]" style="border-radius: 0px">
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
            </form>
          </div>
          <hr style="border: 2px solid">
          <div class="text-center">
            <button type="button" class="btn btn-primary w-25 py-2" data-bs-toggle="modal" data-bs-target="#myModal">Valider From</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
@include('partials._modal_validate')
@endsection
@section('script')
<script>
  $(document).ready(function() {

    $(document).on('click', 'button[type="submit"]', function() {
      $('#myForm').submit();
    });

    // Uniquement que les chiffres ----
    $(document).on('input', '.number', function() {
      this.value = this.value.replace(/[^1-5]/g, '').substring(0, 1);
    });


    $(document).on('click', '.checkbox', function () {
      const id = $(this).val();
      const checked = $(this).is(':checked');
      $('#row' + id).toggleClass('row-disabled', ! checked);
      $('.input' + id).prop('disabled', ! checked);
    });

  })
</script>
@endsection