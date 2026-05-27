@extends('app')
@section('title', ($data ? 'Edit':'Create').' School')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-12 px-4">
      <div class="bg-secondary rounded h-100 p-4">
        <div class="mb-4 d-flex justify-content-between">
          <h5 class="m-2">{{ $data ? 'Edit':'Create' }} School</h5>
          <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
          <a href="{{ route('school.index') }}" class="btn btn-outline-light m-2 py-0">Return</a>
        </div>
        <form action="{{ route($data ? 'school.update':'school.store', $data ? $data->id:'') }}" method="POST">
          @method($data ? 'put':'post')
          @csrf
          <div class="mb-3 py-2">
            <div class="d-flex justify-content-around">
              <h6>Informations Rélatives Etablissement</h6>
              <hr class="w-75" style="border: 2px solid">
            </div>
            <div class="row mb-3">
              <label for="code" class="col-sm-3 col-form-label">Code Etablissement<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" id="code" value="{{ old('code', $data ? $data->code:null) }}" placeholder="Code Etablissement ici ...">
              </div>
            </div>
            <div class="row mb-3">
              <label for="num" class="col-sm-3 col-form-label">Numero Autorisation<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <input type="text" name="num" class="form-control @error('num') is-invalid @enderror" id="num" value="{{ old('num', $data ? $data->autorisation:null) }}" placeholder="Numéro Autorisation ici ...">
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-sm-3 col-form-label">Nom Etablissement<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $data ? $data->name_school:null) }}" placeholder="Nom Etablissement ici ...">
              </div>
            </div>
            <div class="row mb-3" title="{{ $data ? ($data->status ? 'Actif':'Inactif'):null }}" style="display: {{ $data ? null:'none' }}">
              <label for="name" class="col-sm-3 col-form-label"></label>
              <div class="col-sm-9">
                <input type="checkbox" name="status" class="form-check-input" id="status" {{ $data ? ($data->status ? 'checked':null):null }}>
                <label for="status" class="form-check-label mx-2">Status</label>
              </div>
            </div>
          </div>

          <div class="mb-3 py-3" style="display: {{ $data ? 'none':'block' }}">
            <div class="d-flex justify-content-around">
              <h6>Informations Rélatives Admin</h6>
              <hr class="w-75" style="border: 2px solid">
            </div>
            <div class="row mb-4">
              <label for="first" class="col-sm-3 col-form-label">Nom Admin<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <input type="text" name="first" class="form-control @error('first') is-invalid @enderror" id="first" value="{{ old('first') }}" placeholder="Nom Adminitrateur ici ...">
              </div>
            </div>
            <div class="row mb-3">
              <label for="last" class="col-sm-3 col-form-label">Prénoms Admin<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <input type="text" name="last" class="form-control @error('last') is-invalid @enderror" id="last" value="{{ old('last') }}" placeholder="Prénoms Adminitrateur ici ...">
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Sexe Admin<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" name="gender" id="mme" value="mme" @checked(old('gender', 'mr') == 'mme')>
                  <label class="form-check-label" for="mme">Femme</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" name="gender" id="mr" value="mr" @checked(old('gender', 'mr') == 'mr')>
                  <label class="form-check-label" for="mr">Homme</label>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="email" class="col-sm-3 col-form-label">Adresse Email Admin<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" placeholder="Adresse Email Adminitrateur ici ...">
              </div>
            </div>
            <div class="row mb-3">
              <label for="phon" class="col-sm-3 col-form-label">Numéro Téléphone Admin<span class="text-danger">*</span> :</label>
              <div class="col-sm-9">
                <input type="text" name="phon" class="form-control @error('phon') is-invalid @enderror" id="phon" value="{{ old('phon') }}" placeholder="Numéro Téléphone Adminitrateur ici ..." pattern="[0-9]*">
              </div>
            </div>
          </div>
          <hr style="border: 2px solid">
          <div class="text-center">
            <button type="submit" class="btn btn-primary w-25 py-2">Valider From</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
    $('#phon').on('input', function() {
      this.value = this.value.replace(/\D/g, '');
    });
  })
</script>
@endsection