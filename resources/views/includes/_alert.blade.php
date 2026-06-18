<div class="row">
  <div class="col-sm-12 col-xl-6 offset-xl-3">
    @if (session('msg'))
      <div class="alert alert-{{ session('str') }} alert-dismissible fade show mb-0 py-1" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i>{{ session('msg') }}
        <button type="button" class="btn-close p-1 mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
  </div>
</div>