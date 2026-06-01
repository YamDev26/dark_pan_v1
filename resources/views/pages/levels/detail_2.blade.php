@extends('app')
@section('title', 'Detail '.$level['symbol'])
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h4 class="mb-0">Detail {{ $level['symbol'] }}</h4>
          <div class="d-flex">
            <form action="{{ route('level.create', $level['id']) }}" method="get">
              @csrf
              <input type="hidden" name="serie" id="serie">
              <button type="submit" class="btn btn-outline-primary py-1 mx-2">
                Edit
              </button>
            </form>
            <a href="{{ route('level.index') }}" class="btn btn-outline-light py-1">Return</a>
          </div>
        </div>
        <hr>
        <div class="my-2">
          <div class="bg-secondary rounded h-100">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              @foreach ($data as $i => $item)
                <button class="nav-link {{ session('serie') ? (session('serie') == $item['id'] ? 'active' : ''):($loop->first ? 'active' : '') }}" data-id="{{ $item['id'] }}" id="tab-{{ $i }}" data-bs-toggle="tab" data-bs-target="#content-{{ $i }}" type="button" role="tab" aria-controls="content-{{ $i }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                  Série {{ ucwords($item['serie']) }}
                </button>
              @endforeach
            </div>
            <div class="tab-content pt-3" id="nav-tabContent">
              @foreach ($data as $i => $item)
                <div class="tab-pane fade {{ session('serie') ? (session('serie') == $item['id'] ? 'active' : ''):($loop->first ? 'active' : '') }}" id="content-{{ $i }}" role="tabpanel" aria-labelledby="tab-{{ $i }}">
                  @include('partials._table_level', ['data' => $item['matters']])
                </div>
              @endforeach
            </div>
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

    $('#serie').val($('#tab-0').data('id'));
    $('.nav-link').on('click', function() {
      $('#serie').val($(this).data('id'));
    })

  })
</script>
@endsection