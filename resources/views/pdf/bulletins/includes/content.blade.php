<div class="watermark">Bulletin {{ ucwords($cutting->cutting->libelle) }}</div>
<div class="bulletin">
  <!-- ENTETE -->
  @include('pdf.bulletins.includes.header')

  <!-- ETABLISSEMENT -->
  <hr style="width:80%; text-align:center; border: 1px dashed #d6d3d3">
  <table class="table-etablissement">
    <tr>
      <td rowspan="2" class="logo" style="padding:0%">
        <img src="{{ public_path('storage/'. $school->logo) }}" style="width: 80px; height: 80px; margin: 0%">
      </td>
      <td rowspan="2" class="etablissement" style="text-align: center">
        <div style="margin: 0px">
          <strong style="font-size: 18px; line-height: 1.8">{{ strtoupper($school->name) }}</strong>
        </div>
        <div class="school-contact" style="margin: 0px; font-size: 13px">
          {{ $school->email ?? 'Adresse email' }} • {{ $school->addres ?? 'Adresse postale' }} • Tél : {{ $school->phon }}
        </div>
        <span style="color: #d6d3d3">--------------------</span>
        <div style="margin: 0px; font-size: 13px">
          Code : <strong>{{ $school->code }}</strong> • Statut : <strong>{{ ucwords($school->etat) }}</strong>
        </div>
        <span style="color: #d6d3d3">--------------------</span>
      </td>
      <td rowspan="2" class="logo">
        <img src="{{ $qrCode }}" style="width: 80px; height: 80px; margin: 0%">
      </td>
    </tr>
  </table>
  {{-- <hr style="width:20%; text-align:center; border: 1px dashed #d6d3d3; margin-top: 5px"> --}}
  
  <!-- IDENTITE -->
  <table class="table-identite" style="margin-top: 5px;">
    <tr>
      <td class="infos-eleve">
        <div class="nom">
          {{ strtoupper($student->first.' '.$student->last) }} <br>
          • <strong style="font-size: 15px; font-weight:normal; letter-spacing: 0.1px;">{{ $student->matricul }}</strong> •
        </div>

        <table style="width: 100%; margin:0%; padding-left: 12px; font-size: 13px">
          <tr>
            <td>Genre : <strong>{{ $student->genre == 'F' ? 'Feminin':'Masculin' }}</strong></td>
            <td>Classe : <strong>{{ $classe->libelle }}</strong></td>
            <td>Affecté{{ $student->genre == 'F' ? 'e':'' }} : <strong>{{ $student->affecte ? 'Oui':'Non' }}</strong></td>
          </tr>
          <tr>
            <td>Nationalité : <strong>{{ ucwords($student->libelle) }}</strong></td>
            <td>Redoublant{{ $student->genre == 'F' ? 'e':'' }} : <strong>{{ $student->redoubant ? 'Oui':'Non' }}</strong></td>
            <td>Boursi{{ $student->genre == 'F' ? 'ère':'er' }} : <strong>{{ $student->boursier ? 'Oui':'Non' }}</strong></td>
          </tr>
          <tr>
            <td colspan="2">
              Né{{ $student->genre == 'F' ? 'e':'' }} le <strong>{{ date('d/m/Y', strtotime($student->date)) }}</strong> à <strong>{{ ucwords($student->lieu) }}</strong>
            </td>
            <td>Interne : <strong>{{ $student->interne ? 'Oui':'Non' }}</strong></td>
          </tr>
        </table>
      </td>

      <td class="photo" style="padding:0%">
        <img src="{{ public_path('assets/img/student_2.png') }}" alt="" style="width: 90px; height: 80px; margin:0%;">
      </td>
    </tr>
  </table>
</div>