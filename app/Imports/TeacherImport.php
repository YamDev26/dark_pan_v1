<?php

namespace App\Imports;

use App\Services\TeacherService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class TeacherImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    
    public function collection(Collection $data)
    {
        $service = app(TeacherService::class);

        foreach($data as $item) {
    
            $item['date_naissance'] = $this->dates($item['date_naissance'])->format('Y-m-d');
            $item['matiere_enseignee'] = $service->searchMatter($item['matiere_enseignee']);
            $item['piece_identite'] = $this->formatPiece($item['piece_identite']);
            $item['niveau_etude'] = $this->niveauEtude($item['niveau_etude']);
            $item['telephone'] = $this->formatPhon($item['telephone']);
            $item['civilite'] = $this->civilite($item['civilite']);
            $item['date_acquisition'] = $item['date_acquisition'] ? 
            $this->dates($item['date_acquisition'])->format('Y-m-d'):null;

            if(!$service->verifyUser( $item['adresse_email'], $item['telephone'])) {
                $service->getImport($item);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.civilite' => 'required|string',
            '*.nom' => 'required|string',
            '*.prenoms' => 'required|string',
            '*.date_naissance' => 'required|integer',
            '*.lieu_naissance' => 'required|string',
            '*.piece_identite' => 'required|string',
            '*.numero_piece' => 'required|string',
            '*.adresse_email' => 'required|email',
            '*.telephone' => 'required|numeric',
            '*.niveau_etude' => 'required|string',
            '*.denier_diplome' => 'required|string',
            '*.autorisation' => 'required|string',
            '*.numero_autorisation' => 'nullable|string',
            '*.date_acquisition' => 'nullable|integer',
            '*.type_contrat' => 'required|string',
            '*.matiere_enseignee' => 'required|string',
            '*.experience' => 'nullable|integer',
        ];
    }


    private function dates($value, string $format = 'Y-m-d'): ?Carbon {
        if (empty($value)) {
            return null;
        }
        try {
            // Date Excel numérique
            if (is_numeric($value)) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject($value)
                );
            }
            // Format personnalisé
            return Carbon::createFromFormat($format, trim($value));

        } catch (\Throwable $e) {
            // Fallback automatique
            try {
                return Carbon::parse($value);
            } catch (\Throwable $e) {
                return null;
            }
        }
    }


    private function formatPhon($phon) {
        $count = strlen((string) abs($phon));
        return $count == 10 ? $phon:'0'.$phon;
    }


    private function civilite($sexe) {
        return strtolower($sexe) == 'madame' ? 'mde':'mr';
    }


    private function formatPiece($libelle) {
        return match ($libelle) {
            ("Carte Nationale d'Identité") => 'cni',
            default => strtolower($libelle)
        };
    }


    private function niveauEtude(string $libelle): int
    {
        return match ($libelle) {
            'Bac+2' => 2,
            'Bac+3' => 3,
            'Bac+4' => 4,
            'Bac+5' => 5,
            default => 1,
        };
    }
}
