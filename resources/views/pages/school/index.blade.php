@extends('app')
@section('title', 'School Detail')
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="row g-4">
    <div class="col-sm-12">
      <div class="h-100 bg-secondary rounded p-4">
        @if ($school->param)
        <div class="d-flex align-items-center justify-content-between mb-2 px-md-2">
          <h4 class="mb-0">Detail School</h4>
          <a href="{{ route('setting.edit', $school) }}" class="btn btn-outline-primary py-1">Edit</a>
        </div>
        <hr>
        <div class="p-md-3">
          <div class="table-responsive">
            <table style="width: 100%">
              <tbody>
                <tr>
                  <td class="text-center" style="width: 25%">
                    <img class="img-fluid" src="{{ asset('assets/img/testimonial-1.jpg') }}" alt="Logo School" style="width: 60%; height: 60%; border-radius: 5px">
                  </td>
                  <td style="width: 75%">
                    <table class="table text-start align-middle table-bordered mb-0">
                      <tbody>
                        <tr>
                          <th style="width: 40%">Identification</th>
                          <td style="font-size: 18px">{{ $school->code }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">N° Autorisation</th>
                          <td style="font-size: 18px">{{ $school->autorisation }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Nom Etablissement</th>
                          <td style="font-size: 18px">{{ ucwords($school->name_school).($school->slug_school ? ' ~ '.strtoupper($school->slug_school):null) }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Dren</th>
                          <td style="font-size: 18px">{{ ucwords($school->dren_school->libelle) }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Ville</th>
                          <td style="font-size: 18px">{{ ucwords($school->ville_school) }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Adresse Postale</th>
                          <td style="font-size: 18px">{{ $school->addres_postal }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Adresse Email</th>
                          <td style="font-size: 18px">{{ $school->email_school }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">N° Téléphonique</th>
                          <td style="font-size: 18px">{{ $school->phon_school }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Cycle</th>
                          <td style="font-size: 18px">{{ 'Cycle '.(($school->cycle1 ? 'I':'').($school->cycle2 ? ' et II':'')) }}</td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Date Création / Ouverture</td>
                          <td style="font-size: 18px">
                            {{ (date('d.m.Y', strtotime($school->created)) ?? '---').' / '.(date('d.m.Y', strtotime($school->opening)) ?? '---') }}
                          </td>
                        </tr>
                        <tr>
                          <th style="width: 40%">Homologation {{ 'Cycle '.(($school->cycle1 ? 'I':'').($school->cycle2 ? ' et II':'')) }}</th>
                          <td style="font-size: 18px">
                            {{ ($school->date1 ?? '---').' / '.($school->date2 ?? '---') }}
                          </td>
                        </tr>
                      </tbody>
                  </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        @else
          <div class="py-4 text-center">
            <h4 class="my-3">Passez au parametrage de l'établissement</h4>
            <a href="{{ route('setting.edit', $school) }}" class="btn btn-outline-primary py-1">Parametre</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection