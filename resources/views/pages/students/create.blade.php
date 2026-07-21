@extends('app')
@section('title', ($data ? 'Edit':'Create').' Student')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-12 px-4">
      <div class="bg-secondary rounded h-100 p-4">
        <div class="mb-4 d-flex justify-content-between" style="border-bottom: 1px solid #6C7293">
          <h4 class="m-2">{{ $data ? 'Edit':'Nouvel' }} Elève</h4>
          <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
          <div class="d-flex">
            @if (!$data)
              <button type="button" class="btn btn-outline-danger m-2" data-bs-toggle="modal" data-bs-target="#AddModal" style='font-size: 12px'>Import</button>
            @endif
            <a href="{{ route('student.index') }}" class="btn btn-outline-light m-2" style='font-size: 12px'>Return</a>
          </div>
        </div>
        <form action="{{ route($data ? 'student.update':'student.store', $data ? $data->id:'') }}" method="POST" id="myForm">
          @method($data ? 'put':'post')
          @csrf
          <div class="mb-3 py-2">
            <div class="d-flex justify-content-around">
              <h5>Informations Elève</h5>
              <hr class="w-75" style="border: 1px solid">
            </div>
            <div class="row px-sm-3">
              <div class="col-12 col-sm-6 mb-2">
                <label for="matricul" class="col-form-label">Matricule<span class="text-danger">*</span> :</label>
                <input type="text" name="matricul" class="form-control @error('matricul') is-invalid @enderror" id="matricul" value="{{ old('matricul', $data ? $data->student->matricul:null) }}" placeholder="Matricule Elève">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="num" class="col-form-label mb-2">Genre<span class="text-danger">*</span> :</label> <br>
                <div class="form-check form-check-inline ml-2">
                  <input type="radio" name="genre" class="form-check-input @error('genre') is-invalid @enderror" id="feminin" value="F" {{ $data ? ($data->student->genre == 'F' ? 'checked':''):(old('genre') == 'F' ? 'checked':'') }}>
                  <label class="form-check-label" for="feminin">Feminin</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="genre" class="form-check-input @error('genre') is-invalid @enderror" id="masculin" value="M" {{ $data ? ($data->student->genre == 'M' ? 'checked':''):(old('genre') ? (old('genre') == 'M' ? 'checked':''):'checked') }}>
                  <label class="form-check-label" for="masculin">Masculin</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="first" class="col-form-label">Nom<span class="text-danger">*</span> :</label>
                <input type="text" name="first" class="form-control @error('first') is-invalid @enderror" id="first" value="{{ old('first', $data ? $data->student->first:null) }}" placeholder="Nom Elève">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="last" class="col-form-label">Prénoms<span class="text-danger">*</span> :</label>
                <input type="text" name="last" class="form-control @error('last') is-invalid @enderror" id="last" value="{{ old('last', $data ? $data->student->last:null) }}" placeholder="Prénoms Elève">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="date" class="col-form-label">Date de naissance<span class="text-danger">*</span> :</label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="date" value="{{ old('date', $data ? $data->student->date:null) }}">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="lieu" class="col-form-label">Lieu de naissance<span class="text-danger">*</span> :</label>
                <input type="text" name="lieu" class="form-control @error('lieu') is-invalid @enderror" id="lieu" value="{{ old('lieu', $data ? $data->student->lieu:null) }}" placeholder="Lieu Naissance Elève">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="nation" class="col-form-label">Notionalité<span class="text-danger">*</span> :</label>
                <input type="text" name="nation" class="form-control @error('nation') is-invalid @enderror" id="nation" value="{{ old('nation', $data ? $data->student->notionalitie->libelle:null) }}" placeholder="Nationalité Elève">
              </div>
              <div class="col-12 col-sm-6 mb-3">
                <label for="residence" class="col-form-label">Lieu d'habitation<span class="text-danger">*</span> :</label>
                <input type="text" name="residence" class="form-control @error('residence') is-invalid @enderror" id="residence" value="{{ old('residence', $data ? $data->residence:null) }}" placeholder="Lieu Habitation Elève">
              </div>
            </div>
            <div class="col-12 mb-2 px-3 pt-2" style="display: {{ $data ? '':'none' }}">
              <div class="form-check">
                <input type="checkbox" name="status" class="form-check-input" id="status" {{ $data ? ($data->status ? 'checked':''):'disabled' }}>
                <label class="form-check-label" for="status">Status ({{ $data ? ($data->status ? 'Actif':'Inactif'):'' }})</label>
              </div>
            </div>
          </div>
          <div class="mb-3 py-3">
            <div class="d-flex justify-content-around">
              <h5>Autres Informations</h5>
              <hr class="w-75" style="border: 1px solid">
            </div>
            <div class="row px-sm-3">
              <div class="col-12 col-sm-6 mb-2">
                <label for="num" class="col-form-label mb-2">Type<span class="text-danger">*</span> :</label> <br>
                <div class="form-check form-check-inline ml-2">
                  <input type="radio" name="type" class="form-check-input @error('type') is-invalid @enderror" id="parent" value="parent" {{ $data ? ($data->type == 'parent' ? 'checked':''):(old('type') == 'parent' ? 'checked':'') }}>
                  <label class="form-check-label" for="parent">Parent</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="type" class="form-check-input @error('type') is-invalid @enderror" id="tuteur" value="tuteur" {{ $data ? ($data->type == 'tuteur' ? 'checked':''):(old('type') ? (old('type') == 'tuteur' ? 'checked':''):'checked') }}>
                  <label class="form-check-label" for="tuteur">Tuteur</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="num" class="col-form-label mb-2">Civilité<span class="text-danger">*</span> :</label> <br>
                <div class="form-check form-check-inline ml-2">
                  <input type="radio" name="civilit" class="form-check-input @error('civilit') is-invalid @enderror" id="madame" value="Mde" {{ $data ? ($data->tuteur->civilit == 'Mde' ? 'checked':''):(old('civilit') == 'Mde' ? 'checked':'') }}>
                  <label class="form-check-label" for="madame" >Madame</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="civilit" class="form-check-input @error('civilit') is-invalid @enderror" id="monsieur" value="Mr" {{ $data ? ($data->tuteur->civilit == 'Mr' ? 'checked':''):(old('civilit') ? (old('civilit') == 'Mr' ? 'checked':''):'checked') }}>
                  <label class="form-check-label" for="monsieur">Monsieur</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="nom" class="col-form-label">Nom<span class="text-danger">*</span> :</label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" id="nom" value="{{ old('nom', $data ? $data->tuteur->first:null) }}" placeholder="Nom">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="prenom" class="col-form-label">Prénoms<span class="text-danger">*</span> :</label>
                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" id="prenom" value="{{ old('prenom', $data ? $data->tuteur->last:null) }}" placeholder="Prénoms">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="telephon" class="col-form-label">Téléphone<span class="text-danger">*</span> :</label>
                <input type="text" name="telephon" class="form-control @error('telephon') is-invalid @enderror" id="telephon" value="{{ old('telephon', $data ? $data->tuteur->telephon:null) }}" placeholder="Numéro Téléphone">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="email" class="col-form-label">Adresse Email :</label>
                <input type="email" name="email" class="form-control bg-dark @error('email') is-invalid @enderror" id="email" value="{{ old('email', $data ? $data->tuteur->email:null) }}" placeholder="Adresse Email">
              </div>
            </div>
          </div>
          <hr style="border: 1px solid">
          <div class="text-center">
            <button type="button" class="btn btn-primary w-25 py-1" data-bs-toggle="modal" data-bs-target="#myModal">Valider</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" >
    <div class="modal-content pb-0" style="background: #191C24">
      <div class="modal-header py-2">
        <h5 class="modal-title" id="myModalLabel">Confirmation</h5>
        <h3><i class="fa fa-user-edit text-primary"></i></h3>
      </div>
      <div class="modal-body py-4">
        <div class="text-center">
          Cette action peut avoir des modifictions majeurs !
          <p>Cliquez sur 'Oui' pour continuez.</p>
          <i class="fa fa-check-circle" style="font-size: 30px"></i>
        </div>
      </div>
      <div class="modal-footer mb-0">
        <button type="button" class="btn btn-outline-primary py-1" data-bs-dismiss="modal" style="font-size: 14px">Fermer</button>
        <button type="submit" class="btn btn-outline-light py-1" style="font-size: 14px">Valider</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
@include('partials._modal_import',[
  'url' => route('student.import'),
  'export' => route('student.export')
])
@endsection
@section('script')
<script>
  $(document).ready(function() {

    // Uniquement que les chiffres ----
    $('#telephon').on('input', function() {
      this.value = this.value.replace(/\D/g, '').substring(0, 10);;
    });


    // Envoie du formulaire
    $('button[type="submit"]').on('click', function () {
      $('#myForm').submit(); 
    });
    
  })
</script>
@endsection