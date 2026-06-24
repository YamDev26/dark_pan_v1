<?php

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
