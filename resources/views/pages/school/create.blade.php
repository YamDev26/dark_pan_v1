@extends('app')
@section('title', 'Setting Config')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-12 px-4">
      <div class="bg-secondary rounded h-100 p-4">
        <div class="mb-4 d-flex justify-content-between" style="border-bottom: 1px solid #6C7293">
          <h5 class="m-2">{{ $data ? 'Edit':'Create' }} School</h5>
          <p style="text-align:left">Les champs avec astérisque (<span class="text-danger" style="font-size: 17px">*</span>) sont obligatoires.</p>
          <a href="{{ route('setting.index') }}" class="btn btn-outline-light m-2 py-1">Return</a>
        </div>
        <form action="{{ route('setting.update', $data->id) }}" method="POST" id="myForm" enctype="multipart/form-data">
          @method('put')
          @csrf
          <div class="mb-3 py-2">
            <div class="d-flex justify-content-around">
              <h6>Premières Informations</h6>
              <hr class="w-75" style="border: 1px solid">
            </div>
            <div class="row">
              <div class="col-12 col-sm-6 mb-2">
                <label for="code" class="col-form-label">Code Etablissement<span class="text-danger">*</span> :</label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" id="code" value="{{ old('code', $data ? $data->code:null) }}" placeholder="Code Etablissement">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="num" class="col-form-label">Numero Autorisation<span class="text-danger">*</span> :</label>
                <input type="text" name="num" class="form-control @error('num') is-invalid @enderror" id="num" value="{{ old('num', $data ? $data->autorisation:null) }}" placeholder="Numéro Autorisation">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="name" class="col-form-label">Nom Etablissement<span class="text-danger">*</span> :</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $data ? $data->name:null) }}" placeholder="Nom Etablissement">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="slug" class="col-form-label">Slug (Nom en Abréviation Etablissement) :</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" id="slug" value="{{ old('name', $data ? $data->slug:null) }}" placeholder="Slug Etablissement">
              </div>
            </div>
          </div>

          <div class="mb-3 py-3">
            <div class="d-flex justify-content-around">
              <h6>Informations Sécondaires</h6>
              <hr class="w-75" style="border: 1px solid">
            </div>
            <div class="row">
              <div class="col-12 col-sm-6 mb-2">
                <label for="dren" class="col-form-label">DREN Etablissement<span class="text-danger">*</span> :</label>
                <select name="dren" id="dren" class="form-select" aria-label="Default select example">
                  <option selected="">DREN Etablissement</option>
                  @foreach ($dren as $item)
                    <option value="{{ $item['id'] }}" {{ $data->dren_school_id == $item['id'] ? 'selected':'' }}>{{ ucwords($item['libelle']) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="ville" class="col-form-label">Ville Etablissement<span class="text-danger">*</span> :</label>
                <input type="text" name="ville" class="form-control @error('ville') is-invalid @enderror" id="ville" value="{{ old('ville', $data ? $data->city:null) }}" placeholder="Ville Etablissement">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="address" class="col-form-label">Adresse Postale Etablissement<span class="text-danger">*</span> :</label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" id="address" value="{{ old('address', $data ? $data->addres:null) }}" placeholder="Adresse Postale Etablissement">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="email" class="col-form-label">Adresse Emaail Etablissement<span class="text-danger">*</span> :</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $data ? $data->email:null) }}" placeholder="Adresse Emaail Etablissement">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="phon" class="col-form-label">Contact Etablissement<span class="text-danger">*</span> :</label>
                <input type="text" name="phon" class="form-control @error('phon') is-invalid @enderror" id="phon" value="{{ old('phon', $data ? $data->phon:null) }}" placeholder="Contact Etablissement">
              </div>
              <div class="col-12 col-sm-6 mb-2">
                <label for="logo" class="col-form-label">Logo Etablissement<span class="text-danger">*</span> :</label>
                <input type="file" name="file" class="form-control bg-dark @error('logo') is-invalid @enderror" id="logo" value="{{ old('logo') }}" placeholder="Logo Etablissement">
              </div>
            </div>
          </div>

          <div class="mb-3 py-2">
            <div class="d-flex justify-content-around">
              <h6>Autres Informations</h6>
              <hr class="w-75" style="border: 1px solid">
            </div>
            <div class="row">
              <div class="col-2 col-sm-2 mb-2">
                <label class="col-form-label">Statut Etablissement<span class="text-danger">*</span> :</label>
                <div class="pt-1">
                  <div class="form-check form-check-inline">
                    <input type="radio" name="etat" id="prive" value="prive" class="form-check-input" {{ $data ? ($data->etat == 'prive' ? 'checked':''):'checked' }}>
                    <label class="form-check-label" for="prive">Privé</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="etat" id="public" value="public" class="form-check-input" {{ $data ? ($data->etat == 'public' ? 'checked':''):'' }}>
                    <label class="form-check-label" for="public">Public</label>
                  </div>
                </div>
              </div>
              <div class="col-2 col-sm-2 mb-3">
                <label class="col-form-label">Cycle Etablissement<span class="text-danger">*</span> :</label>
                <div class="pt-1">
                  <div class="form-check form-check-inline">
                    <input type="checkbox" name="cycle1" class="form-check-input" id="cycle1" {{ $data ? ($data->cycle1 ? 'checked':''):'' }}>
                    <label class="form-check-label" for="cycle1">Cycle 1</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="checkbox" name="cycle2" class="form-check-input" id="cycle2" {{ $data ? ($data->cycle2 ? 'checked':''):'' }}>
                    <label class="form-check-label" for="cycle2">Cycle 2</label>
                  </div>
                </div>
              </div>
              <div class="col-2 col-sm-2 mb-3">
                <label class="col-form-label">Gestion de la Caisse<span class="text-danger">*</span> :</label>
                <div class="pt-1">
                  <div class="form-check form-check-inline">
                    <input type="radio" name="caisse" class="form-check-input" id="ouiPaye" value="oui" {{ $data ? ($data->caisse ? 'checked':''):'' }}>
                    <label class="form-check-label" for="ouiPaye">Oui</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="caisse" class="form-check-input" id="nonPaye" value="non" {{ $data ? ($data->caisse ? '':'checked'):'' }}>
                    <label class="form-check-label" for="nonPaye">Non</label>
                  </div>
                </div>
              </div>
              <div class="col-2 col-sm-2 mb-3">
                <label class="col-form-label">Gestion des Notes<span class="text-danger">*</span> :</label>
                <div class="pt-1">
                  <div class="form-check form-check-inline">
                    <input type="radio" name="note" class="form-check-input" id="ouiNote" value="oui" {{ $data ? ($data->notes ? 'checked':''):'' }}>
                    <label class="form-check-label" for="ouiNote">Oui</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="note" class="form-check-input" id="nonNote" value="non" {{ $data ? ($data->notes ? '':'checked'):'' }}>
                    <label class="form-check-label" for="nonNote">Non</label>
                  </div>
                </div>
              </div>
              <div class="col-2 col-sm-2 mb-3">
                <label class="form-label">Informatique<span class="text-danger">*</span> :</label><br>
                <div class="pt-1">
                  <div class="form-check form-check-inline">
                    <input type="radio" name="informatik" class="form-check-input" id="ouiInformatik" value="oui" {{ $data ? ($data->informatik ? 'checked':''):'' }}>
                    <label class="form-check-label" for="ouiInformatik">Oui</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="informatik" class="form-check-input" id="nonInformatik" value="non" {{ $data ? ($data->informatik ? '':'checked'):'' }}>
                    <label class="form-check-label" for="nonInformatik">Non</label>
                  </div>
                </div>
              </div>
              <div class="col-2 col-sm-2 mb-3">
                <label class="col-form-label">Musique / Arts - Plastique<span class="text-danger">*</span> :</label><br>
                <div class="pt-1">
                  <div class="form-check form-check-inline">
                    <input type="radio" name="autres" class="form-check-input" id="ouiAutres" value="oui" {{ $data ? ($data->autres ? 'checked':''):'' }}>
                    <label class="form-check-label" for="ouiAutres">Oui</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="autres" class="form-check-input" id="nonAutres" value="non" {{ $data ? ($data->autres ? '':'checked'):'' }}>
                    <label class="form-check-label" for="nonAutres">Non</label>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 mb-3">
                <label for="created" class="col-form-label">Date Création Etablissement<span class="text-danger">*</span> :</label>
                <input type="date" name="created" class="form-control @error('created') is-invalid @enderror" id="created" value="{{ old('created', $data ? $data->created:null) }}">
              </div>
              <div class="col-12 col-sm-6 mb-3">
                <label for="opening" class="col-form-label">Date Ouverture Etablissement<span class="text-danger">*</span> :</label>
                <input type="date" name="opening" class="form-control @error('opening') is-invalid @enderror" id="opening" value="{{ old('opening', $data ? $data->opening:null) }}">
              </div>
              <div class="col-12 col-sm-6 mb-1">
                <label for="date1" class="col-form-label">Décision Autorisation Cycle 1 :</label>
                <input type="text" name="date1" class="form-control @error('date1') is-invalid @enderror" id="date1" value="{{ old('name', $data ? $data->date1:null) }}" placeholder="Décision Autorisation Cycle 1">
              </div>
              <div class="col-12 col-sm-6 mb-1">
                <label for="date2" class="col-form-label">Décision Autorisation Cycle 2 :</label>
                <input type="text" name="date2" class="form-control @error('date2') is-invalid @enderror" id="date2" value="{{ old('name', $data ? $data->date2:null) }}" placeholder="Décision Autorisation Cycle 2">
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
@include('partials._modal_validate')
@endsection
@section('script')
<script>
  $(document).ready(function() {

    // Uniquement que les chiffres ----
    $('#phon').on('input', function() {
      this.value = this.value.replace(/\D/g, '');
    });


    $('input[name="cycle2"]').on('change', function () {
      verifyCheckbox($(this).is(':checked'), 2);
    });

    $('input[name="cycle1"]').on('change', function () {
      verifyCheckbox($(this).is(':checked'), 1);
    });

    $(document).on('click', 'button[type="submit"]', function () {
      $('#myForm').submit(); // Envoie du formulaire
    });


    // Function 
    function verifyCheckbox($check, $valeur) {
      $check ? 
      $('#date'+$valeur).prop('disabled', false):
      $('#date'+$valeur).prop('disabled', true);
    }
  })
</script>
@endsection