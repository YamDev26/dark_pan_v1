@extends('app')
@section('title','List Register')
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
      <h4 class="mb-0">Inscription</h4>
      <div class="d-flex">
        <select class="form-select form-select w-auto border-0 text-color-3 mx-2" id="mySelect">
          <option value="">Inscription ...</option>
          <option value="modal1">Par matricule</option>
          <option value="modal2">Par fichier</option>
        </select>
        {{-- <button type="button" class="btn btn-outline-danger py-1" data-bs-toggle="modal" data-bs-target="#mtlModal">Nouvelle</button>
        <button type="button" class="btn btn-outline-warning py-1 mx-2" data-bs-toggle="modal" data-bs-target="#AddModal">Import</button> --}}
        <select class="form-select form-select w-auto border-0 text-color-3" onchange="window.location.href=this.value;">
          <option value="">Search ...</option>
          @foreach ($levels as $item)
            <option value="{{ route('register.show', $item['id']) }}">{{ ucwords($item['symbol']) }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle" id="myTable">
        <thead>
          <tr class="text-white">
            <th scope="col"></th>
            <th scope="col">Matricule</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénoms</th>
            <th scope="col">Genre</th>
            <th scope="col">Classe</th>
            <th scope="col" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Modal Search Student -->
@include('partials._modal_register_search')
<!-- Modal Add Register -->
@include('partials._modal_register_add',['levels' => $levels])
<!-- Modal Detail -->
@include('partials._modal_register_detail')
<!-- Modal Delete Register -->
@include('partials._modal_register_delete')
<!-- Modal -->
@include('partials._modal_import',[
  'url' => route('classe.import'),
  'export' => null
])
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {

    // Exactement 9 caractères (8 chiffres + une lettre)
    $(document).on('input', '#matricul', function() {
      let value = $(this).val();
      value = value.replace(/[^0-9a-zA-Z]/g, '');
      value = value.slice(0, 9);
      let numbers = value.slice(0, 8).replace(/\D/g, '');
      let letter = value.slice(8, 9).replace(/[^a-zA-Z]/g, '');
      $(this).val(numbers + letter);
    });



    $(document).on('click', '#subMit', function (e) {
      e.preventDefault();
      const mat = $('#matricul').val();

      if (!mat) {
        return;
      }

      $.ajax({
        url: '{{ route('register.create') }}',
        method: 'GET',
        data: { info: mat },
        success: function (data) {
          // Réinitialisation
          $('#block1, #block2, #yesIscrit, #nonIscrit').hide();
          $('input[name="lv2"]').prop('checked', false);
          $('#btnStore').prop('disabled', true);

          if (!data) {
            $('#uderMatricul').text(mat);
            $('#block2').show();
          } 
          else {
            $('#block1').show();
            $('#addName').text(data.name);

            if (data.classe) {
              $('#yesIscrit').show();
            } 
            else {
              $('#nonIscrit').show();
              $('#btnStore').prop('disabled', false);
              const feminin = data.genre === 'F';

              $('#addGenre').text(
                'Genre : ' + (feminin ? 'Feminin' : 'Masculin')
              );

              $('#addNaiss').text(
                `Né${feminin ? 'e' : ''} le ${data.date} à ${data.lieu}`
              );

              $('#addMtcl').text(data.matricul);
              $('#inputMtl').val(data.matricul);
              $('#studentId').val(data.id);

              $('#mtlModal').modal('hide');
              $('#matricul').val('');
            }
          }

          $('#myModal').modal('show');
        }
      });
    });


    $(document).on('change', '#myLevel', function() {
      let option = $(this).find(':selected');
      let level = parseInt(option.val(), 10);
      let symbol = option.data('symbol');
      $('#divLv2, #divSerie').hide();
      $('.serieOption').remove();
      $('input[name="lv2"]').prop('checked', false);
      
      if (level < 3) {              // 6e et 5e
        getClasseLevel(level);
      }
      else if (level >= 3 && level <= 4) { // 4e et 3e
        $('#lv2All').prop('checked', true);
        $('#divLv2').show();
        getClasseLevel(level, 'all');
      }
      else if (level > 4) {         // Second cycle
        $('#divSerie').show();
        getSerieLevel(symbol);
      }
    });


    $(document).on('change', '#mySerie', function() {
      const level = parseInt($('#myLevel').val(), 10);
      const serie = parseInt($(this).val(), 10);
      $('input[name="lv2"]').prop('checked', false);

      const needLv2 = [1, 2, 3].includes(serie) ||
      (level === 5 && serie === 4);

      if (needLv2) {
        $('#lv2All').prop('checked', true);
        $('#divLv2').show();
        getClasseLevel(level, 'all', serie);
      } else {
        $('#divLv2').hide();
        getClasseLevel(level, null, serie);
      }

    });


    $(document).on('change', 'input[name="lv2"]', function() {
      const level = $('#myLevel').val();
      const serie = $('#mySerie').val();
      const lv2 = $(this).val();
      getClasseLevel(level, lv2, serie);
    });


    $(document).on('click', '.dtlBtn', function() {
      const id = $(this).data('id');
      $('#myDetail').attr(
        'action', "{{ route('register.pdf', ':id') }}".replace(':id', id)
      );

      ajax(id, function(data) {
        $('#dtlName').text(data['name']);
        $('#dtlMatricul').text(data['matricul']);
        $('#dtlGenre').text('Genre : '+(data['genre'] == 'F' ? 'Feminin':'Masculin'));
        $('#dtlNaiss').text('Né'+(data['genre'] == 'F' ? 'e ':' ')+data['date']+' à '+data['lieu']);
        $('#dtlResident').text('Résidence : '+data['residence']);
        $('#dtlAffect').text('Affecté'+(data['genre'] == 'F' ? 'e : ':' : ')+data['affect']);
        $('#dtlRedoub').text('Redoubant'+(data['genre'] == 'F' ? 'e : ':' : ')+data['redoubant']);
        $('#dtlBourse').text('Boursi'+(data['genre'] == 'F' ? 'ère : ':'er : ')+data['boursier']);
        $('#dtlLevel').text('Niveau : '+data['level']);
        $('#dtlClasse').text('Classe : '+data['classe']);
        $('#dateIscte').text('Inscrit'+(data['genre'] == 'F' ? 'e le : ':' le : ')+data['inscrit']);
        $('#dtlSerie').text('Série : '+data['serie']);
        $('#dtlLv2').text('Lv2 : '+(data['lv2'] == 'all' ? 'Allemand':'Espagnol'));
        data['serie'] ? $('#dtlSerie').show():$('#dtlSerie').hide();
        data['lv2'] ? $('#dtlLv2').show():$('#dtlLv2').hide();
        $("#DtailModal").modal("show"); // Affichage du modal ...
      });
    });


    $(document).on('click', '.deleteBtn', function() {
      $id = $(this).data('id');
      ajax($id, function(data) {
        console.log(data);
        $('#dtleName').text(data['name']);
        $('#dtleGenre').text(data['genre'] == 'F' ? 'Feminin':'Masculin');
        $('#dtleMatricul').text(data['matricul']);
        $('#dtleClass').text(data['classe']);
        $('#idDelete').val($id);
        $('#DlteModal').modal('show');
      });
    });


    $('#mySelect').on('change', function() {
      switch (this.value) {
        case 'modal1':
          $("#mtlModal").modal("show");
          this.selectedIndex = 0;
          break;
        case 'modal2':
          $("#AddModal").modal("show");
          this.selectedIndex = 0;
          break;
      }
    });


    $('#myTable').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: '{{ route('register.yajra_1') }}',
      columns: [
        {data: 'compte',  className: 'text-center fw-bold', orderable: false, searchable: false },
        {data: 'matricul', className: 'text-left'},
        {data: 'first', className: 'text-left'},
        {data: 'last', className: 'text-left'},
        {data: 'genre', className: 'text-left'},
        {data: 'classe', className: 'text-left'},
        {data: 'action', className: 'text-center', orderable: false, searchable: false},
      ],
      // responsive: true,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
      }
    });


    // Function Get Jquery Create ---

    function getClasseLevel($level, $lv2 = null, $serie = null, ) {
      $('.classeOption').remove();
      $.ajax({
        url: '{{ route('register.classe') }}',
        method: 'GET',
        data: {
          level: $level,
          serie: $serie,
          lv2: $lv2
        },
        success: function(data) {
          for(let i = 0; i < data.length; i++) {
            $('#myClasse').append(
              '<option value="'+data[i].id+'" class="classeOption">'+data[i].libelle+'</option>'
            )
          }
        }
      })
    }

    function getSerieLevel($level) {
      $.ajax({
        url: '{{ route('register.serie') }}',
        method: 'GET',
        data: {
          level: $level
        },
        success: function(data) {
          for(let i = 0; i < data.length; i++) {
            $('#mySerie').append(
              '<option value="'+data[i].id+'" class="serieOption">'+data[i].libelle+'</option>'
            )
          }
        }
      })
    }

    function ajax(id, callback) {
      $.ajax({
        url: '{{ route('register.search') }}',
        method: 'GET',
        data: {id: id},
        success: function(data){
          callback(data);
        }
      })
    }
  });
</script>
@endsection