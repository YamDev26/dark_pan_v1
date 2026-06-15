@extends('app')
@section('title', 'Resultat '.$classe['libelle'])
@section('link')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
  .dataTables_length label, .select-info{
    display: none;
  }

  table.dataTable.no-footer {
    border-bottom: 1px solid black;
  }

  table.dataTable {
    border-collapse: collapse;
  }

  .text-left {
    text-align: left !important;
  }

  .dataTables_filter {
    margin-bottom: 20px
  }
</style>
@endsection
@section('content')
<div class="container-fluid pt-4 px-4">
  <div class="bg-secondary text-center rounded p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2" style="border-bottom: 3px solid #6C7293">
      <h4 class="mb-0">{{ ucwords($cutting['cutting']['libelle']) }}</h4>
      <div class="my-0">
        <h4 class='my-0'>{{ $classe['libelle'] }}</h4>
        <span class="my-0">Statistique</span>
      </div>
      <div class="d-flex">
        <div class="mx-2">
          <select id="mySelect" class="form-select form-select w-auto border-0 text-color-3">
            <option value="">Autres ...</option>
          </select>
        </div>
        <a href="{{ route('resultat.index') }}" class="btn btn-outline-light py-1">Return</a>
      </div>
    </div>
    <div class="row g-4 pt-3">
      <div class="col-sm-12 col-xl-6">
        <h5 class="mb-4 text-left">Cartes statistiques</h5>
        <div class="table-responsive">
          <span class="mb-3">Travail insuffisant</span>
          @include('partials._table_resultat')
        </div>
      </div>
      <div class="col-sm-12 col-xl-6">
        <h5 class="mb-4 text-left">Répartition des moyennes</h5>
        <canvas id="myChart" width="400" height="250"></canvas>
      </div>
      <div class="col-12 mb-2 mt-4">
        <h5 class="mb-0 text-left">Statistique des matières</h5>
        <canvas id="myScale" width="400" height="150" aria-label="Hello ARIA World" role="img"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {

    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['[0, 8.49]', '[8.5, 9.99]', '[10, 11.99]', '[12, 13.99]', '[14, 15.99]', '[16, 20]'],
        datasets: [{
          label: 'Nommbre d\'élève ',
          data: [
            {{ $tranche['moyenne_0_849'] }}, {{ $tranche['moyenne_850_999'] }}, {{ $tranche['moyenne_10_1199'] }},
            {{ $tranche['moyenne_12_1399'] }}, {{ $tranche['moyenne_14_1599'] }}, {{ $tranche['moyenne_16_plus'] }}
          ],
          backgroundColor: [
            '#6C7293',
            '#6C7293',
            '#6C7293',
            '#6C7293',
            '#6C7293',
            '#6C7293'
          ],
          borderColor: [
            '#6C7293',
            '#6C7293',
            '#6C7293',
            '#6C7293',
            '#6C7293',
            '#6C7293'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });


    // Statistique des matières
    const ctxs = $('#myScale');
    new Chart(ctxs, {
      type: 'line',
      data: {
        labels: [
          @foreach ($matieres as $matiere)
            '{{ $matiere->symbol }}',
          @endforeach
        ],
        datasets: [{
          label: 'Taux de réussite ',
          data: [
            @foreach ($resultmatters as $item)
            '{{ $item->valeur ?? 0 }}',
            @endforeach
          ],
          backgroundColor: '#6C7293',
          borderColor: '#6C7293',
          borderWidth: 2
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function (value) {
                return value + '%';
              }
            }
          }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function (context) {
                return context.dataset.label + ': ' + context.parsed.y + '%';
              }
            }
          }
        }
      }
    });

  })
</script>
@endsection