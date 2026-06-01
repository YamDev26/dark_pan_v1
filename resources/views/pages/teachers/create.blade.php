@extends('app')
@section('title', ($data ? 'Edit':'Create').' Teacher')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-12 px-4">
      <div class="bg-secondary rounded h-100 p-4">
        <div class="mb-4 d-flex justify-content-between" style="border-bottom: 3px solid #6C7293">
          <h4 class="m-2">{{ $data ? 'Edit':'Nouvel' }} Enseignant</h4>
          <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
          <div class="d-flex">
            <button type="button" class="btn btn-outline-danger m-2 py-1" data-bs-toggle="modal" data-bs-target="#AddModal">Export</button>
            <a href="{{ route('student.index') }}" class="btn btn-outline-light m-2 py-1">Return</a>
          </div>
        </div>
        <form action="{{ route('teacher.store') }}" method="POST" id="myForm">
          @method($data ? 'put':'post')
          @csrf
          <div class="mb-3 py-2">
            <div class="d-flex justify-content-around">
              <h5>Informations Personnelles</h5>
              <hr class="w-75" style="border: 2px solid">
            </div>
            <div class="row px-sm-3">
              <div class="col-4 mb-2">
                <label class="col-form-label mb-2">Type Enseignant<span class="text-danger">*</span> :</label> <br>
                <div class="form-check form-check-inline ml-2">
                  <input type="radio" name="enseignant" class="form-check-input @error('enseignant') is-invalid @enderror" id="permanent" value="permanent" checked>
                  <label class="form-check-label" for="permanent">Permanent</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="enseignant" class="form-check-input @error('enseignant') is-invalid @enderror" id="vacataire" value="vacataire">
                  <label class="form-check-label" for="vacataire">Vacataire</label>
                </div>
              </div>
              <div class="col-4 mb-2">
                <label class="col-form-label mb-2">Autorisation d'enseigner<span class="text-danger">*</span> :</label> <br>
                <div class="form-check form-check-inline ml-2">
                  <input type="radio" name="autorisate" class="form-check-input @error('autorisate') is-invalid @enderror" id="non" value="non">
                  <label class="form-check-label" for="non">Non</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="autorisate" class="form-check-input @error('autorisate') is-invalid @enderror" id="oui" value="oui" checked>
                  <label class="form-check-label" for="oui">Oui</label>
                </div>
              </div>
              <div class="col-4 mb-2">
                <label class="col-form-label mb-2">Civilité<span class="text-danger">*</span> :</label> <br>
                <div class="form-check form-check-inline ml-2">
                  <input type="radio" name="civility" class="form-check-input @error('civility') is-invalid @enderror" id="feminin" value="mme">
                  <label class="form-check-label" for="feminin">Madame</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="civility" class="form-check-input @error('civility') is-invalid @enderror" id="masculin" value="mr" checked>
                  <label class="form-check-label" for="masculin">Monsieur</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="first" class="col-form-label">Nom<span class="text-danger">*</span> :</label>
                <input type="text" name="first" class="form-control @error('first') is-invalid @enderror" id="first" value="{{ old('first', $data ? $data->student->first:null) }}" placeholder="Nom Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="last" class="col-form-label">Prénoms<span class="text-danger">*</span> :</label>
                <input type="text" name="last" class="form-control @error('last') is-invalid @enderror" id="last" value="{{ old('last', $data ? $data->student->last:null) }}" placeholder="Prénoms Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="date" class="col-form-label">Date de naissance<span class="text-danger">*</span> :</label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="date" value="{{ old('date', $data ? $data->student->date:null) }}">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="lieu" class="col-form-label">Lieu de naissance<span class="text-danger">*</span> :</label>
                <input type="text" name="lieu" class="form-control @error('lieu') is-invalid @enderror" id="lieu" value="{{ old('lieu', $data ? $data->student->lieu:null) }}" placeholder="Lieu Naissance Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="piece" class="col-form-label">Pièce d'identité<span class="text-danger">*</span> :</label>
                <select name="piece" class="form-select @error('piece') is-invalid @enderror" id="piece" aria-label="Default select example">
                  <option value="">Select ...</option>
                  <option value="cni" {{ old('piece') == 'cni' ? 'selected':'' }}>Carte national d'identité</option>
                  <option value="passport" {{ old('piece') == 'passport' ? 'selected':'' }}>PassPort</option>
                  <option value="permis" {{ old('piece') == 'permis' ? 'selected':'' }}>Parmis de conduite</option>
                </select>
              </div>
              <div class="col-12 col-sm-6 mb-3">
                <label for="numero" class="col-form-label">Numéro de pièce<span class="text-danger">*</span> :</label>
                <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="numero" value="{{ old('numero', $data ? $data->residence:null) }}" placeholder="Numéro Pièce Enseignant">
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
              <hr class="w-75" style="border: 2px solid">
            </div>
            <div class="row px-sm-3">
              <div class="col-12 col-sm-6 mb-2">
                <label for="email" class="col-form-label">Adresse Email<span class="text-danger">*</span> :</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $data ? $data->email:null) }}" placeholder="Adresse Email Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="phon" class="col-form-label">Téléphone<span class="text-danger">*</span> :</label>
                <input type="text" name="phon" class="form-control @error('phon') is-invalid @enderror" id="phon" value="{{ old('phon', $data ? $data->phon:null) }}" placeholder="Numéro Téléphonique Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="etude" class="col-form-label">Niveau d'étude<span class="text-danger">*</span> :</label>
                <select name="etude" class="form-select @error('etude') is-invalid @enderror" id="etude" aria-label="Default select example">
                  <option value="">Select ...</option>
                  <option value="1" {{ old('etude') == '1' ? 'selected':'' }}>Bac+1</option>
                  <option value="2" {{ old('etude') == '2' ? 'selected':'' }}>Bac+2</option>
                  <option value="3" {{ old('etude') == '3' ? 'selected':'' }}>Bac+3</option>
                  <option value="4" {{ old('etude') == '4' ? 'selected':'' }}>Bac+4</option>
                  <option value="5" {{ old('etude') == '5' ? 'selected':'' }}>Bac+5</option>
                </select>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="diplom" class="col-form-label">Dernier diplôme<span class="text-danger">*</span> :</label>
                <input type="text" name="diplom" class="form-control @error('diplom') is-invalid @enderror" id="diplom" value="{{ old('diplom', $data ? $data->phon:null) }}" placeholder="Dernier Diplôme Enseignant">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="num_auto" class="col-form-label">Numéro autorisation<span class="text-danger">*</span> :</label>
                <input type="text" name="num_auto" class="form-control @error('num_auto') is-invalid @enderror" id="num_auto" value="{{ old('num_auto', $data ? $data->tuteur->telephon:null) }}" placeholder="Numéro Autorisation">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="date_acquise" class="col-form-label">Date d'acquisition :</label>
                <input type="date" name="date_acquise" class="form-control @error('date_acquise') is-invalid @enderror" id="date_acquise" value="{{ old('date_acquise', $data ? $data->tuteur->email:null) }}">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="matter" class="col-form-label">Matière dispensée<span class="text-danger">*</span> :</label>
                <select name="matter" class="form-select @error('matter') is-invalid @enderror" id="matter">
                  <option value="">Select ...</option>
                  @foreach ($matters as $item)
                    <option value="{{ $item['id'] }}" {{ old('matter') == $item['id'] ? 'selected':'' }}>{{ ucwords($item['symbol']) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="experiens" class="col-form-label">Expérience professionnelle<span class="text-danger">*</span> :</label>
                <input type="text" name="experiens" class="form-control @error('experiens') is-invalid @enderror" id="experiens" value="{{ old('experiens', $data ? $data->phon:null) }}" placeholder="Expérience Professionnelle Enseignant">
              </div>
            </div>
          </div>
          <hr style="border: 2px solid">
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
@endsection
@section('script')
<script>
  $(document).ready(function() {

    // Uniquement que les chiffres ----
    $('#phon').on('input', function() {
      this.value = this.value.replace(/\D/g, '').substring(0, 10);;
    });

    $('#experiens').on('input', function() {
      this.value = this.value.replace(/\D/g, '').substring(0, 2);;
    });


    // Envoie du formulaire
    $('button[type="submit"]').on('click', function () {
      $('#myForm').submit(); 
    });
    
  })
</script>
@endsection