<?php

  if(!function_exists('getUserGlobal')) {
    function getUserGlobal() {
      return auth()->user();
    }
  }


  if(!function_exists('formatNameUser')) {
    function formatNameUser() {
      $user = auth()->user();
      $lettre = mb_substr($user->last_name, 0, 2);
      return ucwords($user->civility .' '. $user->first_name .' '. $lettre.'...');
    }
  }


  if(!function_exists('getUserRole')) {
    function getUserRole() {
      return auth()->user()->role->libelle;
    }
  }


  if(!function_exists('getUserDashboard')) {
    function getUserDashboard() {
      return match (getUserRole()) {
        'enseignant' => 'enseigmnt',
        default => 'admin'
      };
    }
  }


  if(!function_exists('getUserMenus')) {
    function getUserMenus() {
      $role = getUserRole();
      return match (true) {
        $role == 'enseignant' => '_enseigmnt',
        $role == 'SuperAdmin' => '_admin',
        in_array($role, ['admin', 'fondateur', 'directeur']) => '_autre'
      };
    }
  }


  // Gestion Classement Student --------------------
  if(!function_exists('ClassementStudent')) {
    function ClassementStudent(array $data): array {
      // Tri décroissant des moyennes
      usort($data, static function ($a, $b) {
        $aMoyen = $a['moyen'] === 'nc' ? -INF : (float) $a['moyen'];
        $bMoyen = $b['moyen'] === 'nc' ? -INF : (float) $b['moyen'];
        return $bMoyen <=> $aMoyen;
      });

      $previous = null;
      $displayRank = 0;
      $realRank = 0;

      foreach ($data as $key => $item) {
        $moyen = $item['moyen'];
        // Ignorer les non classés
        if ($moyen === 'nc') {
          $data[$key]['rang'] = '--';
          continue;
        }
        $realRank++;
        // Ex-aequo
        if ($previous !== null && $previous == $moyen) {
          $data[$key]['rang'] = $displayRank . 'ex';
        } 
        else {
          $displayRank = $realRank;
          if ($displayRank === 1) {
            $suffix = $item['genre'] === 'F' ? 'ère' : 'er';
          } else {
            $suffix = 'ème';
          }
          $data[$key]['rang'] = $displayRank . $suffix;
        }
        $previous = $moyen;
      }
      return $data;
    }
  }


  // Calcul General Moyenne Matiere, Bilan, Trimestre
  if(!function_exists('moyenneCalcul')) {
    function moyenneCalcul($total = null, $coeff = null) {
      if(blank($total) || blank($coeff)) {
        return 'nc';
      }
      if(!($total > 0 || $coeff > 0)) {
        return '00';
      }
      $result = (string) number_format(($total / $coeff), 2, '.', '');
      return $result < 10 ? '0' . $result : $result;
    }
  }


  // Emploi du temps afficher la matière
  if(!function_exists('getMatterTable')) {
    function getMatterTable($day, $time, $period, $data) {

      $item = $data->first(fn ($item) =>
        $item['slot_time_id'] == $time
        && $item['days_week_id'] == $day
        && $item['period'] == $period
      );

      if (! $item) {
        return null;
      }
      
      return $item->level_matter->matter->symbol;
    }
  }


  // Emploi du temps afficher la matière pour edition
  if(!function_exists('editMatterTable')) {
    function editMatterTable($day, $time, $matter, $period, $data) {
      $item = $data->first(fn ($item) =>
        $item['slot_time_id'] == $time
        && $item['days_week_id'] == $day
        && $item['level_matter_id'] == $matter
        && $item['period'] == $period
      );
      return $item ? 'selected':null;
    }
  }


  // Emploi du temps afficher la matière pour edition
  if(!function_exists('getLibelleCutting')) {
    function getLibelleCutting($libelle) {
      list($lib, $val) = explode(' ', $libelle);
      return ($val == 1 ? '1er ':$val.'eme ').strtoupper($lib);
    }
  }


  // Retourne l'appréciation selon la moyenne Par matièrer ..................
  if(!function_exists('mentionMsg')) {
    function mentionMsg($moyenne){
      return match (true) {
      $moyenne >= 18 => 'Félicitation',
      $moyenne >= 16 => 'Très bien',
      $moyenne >= 14 => 'Bien',
      $moyenne >= 12 => 'Assez bien',
      $moyenne >= 10 => 'Passable',
      $moyenne >= 8  => 'Avertissement',
      $moyenne >= 0  => 'Blâme',
      default => '---',
    };
    }
  }


  // Appréciation Pour Enseignement Général
  if(!(function_exists('appreciationGeneral'))) {
    function appreciation($moyenne){
      return match (true) {
        $moyenne >= 18 => 'Travail remarquable. Félicitations pour vos excellents résultats !',
        $moyenne >= 16 => 'Très bon travail. Continuez sur cette belle dynamique.',
        $moyenne >= 14 => 'Bon travail. Des efforts supplémentaires à fournir.',
        $moyenne >= 12 => 'Travail satisfaisant. Continuez vos efforts pour progresser.',
        $moyenne >= 10 => 'Résultats acceptables. Une implication plus soutenue est souhaitable.',
        $moyenne >= 8  => 'Les résultats demeurent fragiles. Un travail plus régulier est indispensable.',
        $moyenne >= 0  => 'Les résultats sont très insuffisants. Il est nécessaire de redoubler d\'efforts.',
        default => '---',
    };
    }
  }
  

  // Faire un retour à la ligne au 1er point ou virgule
  if(!(function_exists('breakAfterFirstSeparator'))) {
    function breakAfterFirstSeparator($moyenne) {
      $text = appreciation($moyenne);
      return preg_replace('/([,.])/', '$1<br>', $text, 1);
    }
  }


  // Format name Teacher
  if(!(function_exists('formatName'))) {
    function formatName($name) {
      $explode = explode(' ', $name, 2);

      if(count($explode) < 2) {
        return ucwords($name);
      }

      $lettre = mb_substr($explode[0], 0, 1);
      return ucwords($lettre.' '.$explode[1]);
    }
  }


  // Emploi du Temps Affiche Enseignant
  if(!function_exists('getClasseTable')) {
    function getClasseTable($day, $time, $period, $data) {

      $item = $data->first(fn ($item) =>
        $item->time == $time
        && $item->days == $day
        && $item->period == $period
      );

      if (! $item) {
        return null;
      }
      
      return $item->classe.' ['.$item->matter.']';
    }
  }
