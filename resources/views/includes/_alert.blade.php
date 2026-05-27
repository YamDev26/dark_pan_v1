<div class="row">
  <div class="col-sm-12 col-xl-6 offset-xl-3">
    @if (session('msg'))
      <div class="alert alert-{{ session('str') }} alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i>{{ session('msg') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
  </div>
</div>