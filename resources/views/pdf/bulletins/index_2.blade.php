@extends('pdf.bulletin')
@section('link')
<style>
  .bulletin-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Times New Roman', serif;
    font-size: 13px;
  }

  .bulletin-table th,
  .bulletin-table td {
    border: 0.6px solid #000;
    padding: 3px 5px;
    vertical-align: middle;
  }

  .bulletin-table thead th {
    background: #f5f5f5;
    text-align: center;
    font-weight: bold;
  }

  .bulletin-table th.left,
  .bulletin-table td.left {
    text-align: left;
  }

  .bulletin-table td {
    text-align: center;
  }

  .bulletin-table .discipline {
    text-align: left;
    font-weight: bold;
    padding: 3px 5px;
  }

  .bulletin-table .disciplines {
    text-align: left;
    padding: 3px 5px;
  }

  .bulletin-table .note {
    font-weight: bold;
  }

  .bulletin-table tbody tr {
    height: 25px;
  }

  /* Largeurs proches du modèle */
  .bulletin-table th:nth-child(1),
  .bulletin-table td:nth-child(1) {
    width: 25%;
  }

  .bulletin-table th:nth-child(7),
  .bulletin-table td:nth-child(7) {
    width: 25%;
    white-space: nowrap;
  }

  .bulletin-table th:nth-child(8),
  .bulletin-table td:nth-child(8) {
    width: 14%;
  }

  .bulletin-table th:nth-child(9),
  .bulletin-table td:nth-child(9) {
    width: 12%;
  }

  /* Compatible DomPDF */
  table {
    page-break-inside: auto;
  }

  tr {
    page-break-inside: avoid;
  }

  .mytable tr, .mytable td {
    border:none;
    text-align: left
  }
</style>
@endsection  
@section('content')

  @foreach ($resultat as $result)
    <div class="pages">
      @include('pdf.bulletins.includes.content', [
        'school' => $result['school'],
        'classe' => $result['classe'],
        'cutting' => $result['cutting'],
        'student' => $result['student'],
        'qrCode' => $result['qrCode'],
      ])

      <table class="bulletin-table">
        <thead>
          <tr>
            <th rowspan="2" class="left">Disciplines</th>
            <th rowspan="2">Moy</th>
            <th rowspan="2">Coef.</th>
            <th rowspan="2">M.Coef.</th>
            <th rowspan="2">Rang</th>
            <th colspan="3">PROFESSEURS</th>
          </tr>
          <tr>
            <th class="left">Nom et Prénoms</th>
            <th class="left">Appréciations</th>
            <th>Signatures</th>
          </tr>
        </thead>
        <tbody>

          @include('pdf.bulletins.partials.sub_matter', [
            'sunMatter' => $result['sunMatter']
          ])

          @include('pdf.bulletins.partials.matter',[
            'bilans' => $result['bilans'],
            'matters' => $result['matters']
          ])
          
          @include('pdf.bulletins.partials.result_student',[
            'student' => $result['student']
          ])
        </tbody>
      </table>
      @include('pdf.bulletins.partials.statistik2',[
        'result' => $result['result'],
        'school' => $result['school'],
        'student' => $result['student'],
      ])

      <!-- FOOTER -->
      @include('pdf.bulletins.includes.footer',[
        'school' => $result['school'],
        'classe' => $result['classe'],
        'string' => $result['string'],
      ])
    </div>
    @unless($loop->last)
    <div style="page-break-after: always;"></div>
    @endunless
  @endforeach
    
@endsection