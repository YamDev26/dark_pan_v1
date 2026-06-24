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
    }

    .bulletin-table .note {
        font-weight: bold;
    }

    .bulletin-table tbody tr {
        height: 25px;
        /* padding: 10px 0px; */
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

</style>
@endsection  
@section('content')
<table class="bulletin-table">
    <thead>
        <tr>
            <th rowspan="2" class="left">Disciplines</th>
            <th rowspan="2">Moy</th>
            <th rowspan="2">Coef.</th>
            <th rowspan="2">M.Coef.</th>
            <th rowspan="2">Rang</th>
            {{-- <th rowspan="2">M.An</th> --}}
            <th colspan="3">PROFESSEURS</th>
        </tr>
        <tr>
            <th class="left">Nom et Prénoms</th>
            <th class="left">Appréciations</th>
            <th>Signatures</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($bilans as $i => $bilan)
            @foreach ($matters[$bilan->id] ?? [] as $matter)
                <tr>
                    <td class="discipline">{{ $matter->libelle }}</td>
                    <td class="note">{{ $matter->moyenne }}</td>
                    <td>{{ $matter->values }}</td>
                    <td>{{ $matter->total }}</td>
                    <td>{{ $matter->rang }}</td>
                    <td class="left">---</td>
                    <td class="left">---</td>
                    <td></td>
                </tr>
            @endforeach

            <tr style="background: #f5f5f5;">
                <th class="discipline">{{ ucwords($bilan->libelle) }}</th>
                <th class="note">{{ $bilan->moyenne }}</th>
                <th>{{ $bilan->values }}</th>
                <th>{{ $bilan->total ?? '--' }}</th>
                <th>{{ $bilan->rang }}</th>
                <th colspan="3"></th>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection