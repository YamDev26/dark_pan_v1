@extends('app')
@section('title', ($data ? 'Edit':'Create').' Teacher')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-12 px-4">
      <div class="bg-secondary rounded h-100 p-4">
        <div class="mb-4 d-flex justify-content-between" style="border-bottom: 1px solid #6C7293">
          <h4 class="m-2">{{ $data ? 'Edit':'Nouvel' }} Enseignant</h4>
          <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
          <div class="d-flex">
            <button type="button" class="btn btn-outline-danger m-2 py-1" data-bs-toggle="modal" data-bs-target="#AddModal" style="display: {{ $data ? 'none':'' }}">Export</button>
            <button type="button" class="btn btn-outline-danger m-2 py-1" data-bs-toggle="modal" data-bs-target="#DlteModal" style="display: {{ $data ? '':'none' }}">Delete</button>
            <a href="{{ route('user.index') }}" class="btn btn-outline-light m-2 py-1">Return</a>
          </div>
        </div>
        <form action="{{ route($data ? 'teacher.update':'user.store', ($data ? $data['id']:'')) }}" method="POST" id="myForm">
          @method($data ? 'put':'post')
          @csrf
          <div class="mb-3 py-2">
            <div class="d-flex justify-content-around">
              <h5>Informations Personnelles</h5>
              <hr class="w-75" style="border: 1px solid">
            </div>
            <div class="row px-sm-3">
              <div class="col-6 mb-2">
                <label class="col-form-label mb-2">Profil<span class="text-danger">*</span> :</label> <br>
                <select name="role" class="form-select @error('role') is-invalid @enderror" id="role">
                  <option value="">Select ...</option>
                  @foreach ($roles as $item)
                    <option value="{{ $item['id'] }}" {{ old('role') == $item['id'] ? 'selected':'' }} {{ $data ? ($data->teacher->matter_id == $item['id'] ? 'selected':''):'' }}>{{ ucwords($item['libelle']) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-4 mb-2">
                <label class="col-form-label mb-2">Civilité<span class="text-danger">*</span> :</label> <br>
                <div class="form-check form-check-inline ml-2">
                  <input type="radio" name="civility" class="form-check-input @error('civility') is-invalid @enderror" id="feminin" value="mme" {{ $data ? ($data->civility == 'mme' ? 'checked':''): (old('civility') == 'mme' ? 'checked':'') }}>
                  <label class="form-check-label" for="feminin">Madame</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="civility" class="form-check-input @error('civility') is-invalid @enderror" id="masculin" value="mr" {{ $data ? ($data->civility == 'mr' ? 'checked':''): (old('civility') == 'mr' ? 'checked':'checked') }}>
                  <label class="form-check-label" for="masculin">Monsieur</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="first" class="col-form-label">Nom<span class="text-danger">*</span> :</label>
                <input type="text" name="first" class="form-control @error('first') is-invalid @enderror" id="first" value="{{ old('first', $data ? $data->first_name:null) }}" placeholder="Nom Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="last" class="col-form-label">Prénoms<span class="text-danger">*</span> :</label>
                <input type="text" name="last" class="form-control @error('last') is-invalid @enderror" id="last" value="{{ old('last', $data ? $data->last_name:null) }}" placeholder="Prénoms Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="date" class="col-form-label">Date de naissance<span class="text-danger">*</span> :</label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="date" value="{{ old('date', $data ? $data->teacher->date_naiss:null) }}">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="lieu" class="col-form-label">Lieu de naissance<span class="text-danger">*</span> :</label>
                <input type="text" name="lieu" class="form-control @error('lieu') is-invalid @enderror" id="lieu" value="{{ old('lieu', $data ? $data->teacher->lieu_naiss:null) }}" placeholder="Lieu Naissance Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="piece" class="col-form-label">Pièce d'identité<span class="text-danger">*</span> :</label>
                <select name="piece" class="form-select @error('piece') is-invalid @enderror" id="piece" aria-label="Default select example">
                  <option value="">Select ...</option>
                  <option value="cni" {{ old('piece') == 'cni' ? 'selected':'' }} {{ $data ? ($data->teacher->piece == 'cni' ? 'selected':''):'' }}>Carte national d'identité</option>
                  <option value="passport" {{ old('piece') == 'passport' ? 'selected':'' }} {{ $data ? ($data->teacher->piece == 'passport' ? 'selected':''):'' }}>PassPort</option>
                  <option value="permis" {{ old('piece') == 'permis' ? 'selected':'' }} {{ $data ? ($data->teacher->piece == 'permis' ? 'selected':''):'' }}>Parmis de conduite</option>
                </select>
              </div>
              <div class="col-12 col-sm-6 mb-3">
                <label for="numero" class="col-form-label">Numéro de pièce<span class="text-danger">*</span> :</label>
                <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="numero" value="{{ old('numero', $data ? $data->teacher->num_piece:null) }}" placeholder="Numéro Pièce Enseignant">
              </div>
            </div>
          </div>
          <div class="mb-3 pt-3">
            <div class="d-flex justify-content-around">
              <h5>Autres Informations</h5>
              <hr class="w-75" style="border: 1px solid">
            </div>
            <div class="row px-sm-3">
              <div class="col-12 col-sm-6 mb-2">
                <label for="email" class="col-form-label">Adresse Email<span class="text-danger">*</span> :</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $data ? $data->email:null) }}" placeholder="Adresse Email Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="phon" class="col-form-label">Téléphone<span class="text-danger">*</span> :</label>
                <input type="text" name="phon" class="form-control @error('phon') is-invalid @enderror" id="phon" value="{{ old('phon', $data ? $data->telephon:null) }}" placeholder="Numéro Téléphonique Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2" style="display: none">
                <label for="level" class="col-form-label">Matière dispensée<span class="text-danger">*</span> :</label>
                <select name="level" class="form-select @error('level') is-invalid @enderror" id="level">
                  <option value="">Select ...</option>
                  @foreach ($levels as $item)
                    <option value="{{ $item['id'] }}" {{ old('level') == $item['id'] ? 'selected':'' }} {{ $data ? ($data->teacher->matter_id == $item['id'] ? 'selected':''):'' }}>{{ ucwords($item['symbol']) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 px-3 pt-2" style="display: {{ $data ? '':'none' }}">
                <div class="form-check">
                  <input type="checkbox" name="status" class="form-check-input" id="status" {{ $data ? ($data->status ? 'checked':''):'disabled' }}>
                  <label class="form-check-label" for="status">Status ({{ $data ? ($data->status ? 'Actif':'Inactif'):'' }})</label>
                </div>
              </div>
            </div>
          </div>
          <hr class="mx-3" style="border: 1px solid">
          <div class="text-center">
            <button type="button" class="btn btn-primary w-25 py-2" data-bs-toggle="modal" data-bs-target="#myModal">Valider From</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
@include('partials._modal_validate')

<!-- Modal Import FIle -->
@include('partials._modal_import',[
  'url' => route('teacher.import'),
  'export' => route('teacher.export')
])
<!-- Modal Delete -->
<div class="modal" id="DlteModal" tabindex="-1" aria-labelledby="DlteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header py-2">
        <h5 class="modal-title" id="myModalLabel">Delete</h5>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body py-4">
        <div class="text-center">
          <strong id="dtleName" style="font-size: 21px"></strong>
          <h4 class="mt-0" id="dtleClass">Suppression Enseignant</h4>
          Cette action peut avoir des modifictions majeurs !
          <p>Cliquez sur 'Valider' pour continuez.</p>
          <i class="fa fa-trash" style="font-size: 30px"></i>
        </div>
      </div>
      <form action="{{ $data ? route('teacher.delete', $data['id']):'#' }}" method="post">
        @csrf
        @method('put')
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

    $auto = $('input[name="autorisate"]:checked').val();
    $auto == "oui" ? $('#divAuto').show():$('#divAuto').hide();

    // Uniquement que les chiffres ----
    $('#phon').on('input', function() {
      this.value = this.value.replace(/\D/g, '').substring(0, 10);;
    });

    $('#experiens').on('input', function() {
      this.value = this.value.replace(/\D/g, '').substring(0, 2);;
    });

    $('input[name="autorisate"]').on('click', function() {
      $(this).val() == 'oui' ? $('#divAuto').show():$('#divAuto').hide();
    });

    // Envoie du formulaire
    $('button[type="submit"]').on('click', function () {
      $('#myForm').submit(); 
    });
    
  })
</script>
@endsection