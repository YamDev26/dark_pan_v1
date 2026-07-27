<?php

namespace App\Jobs\Matters;

use App\Jobs\MoyenneEditJob;
use App\Services\GestionNoteService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculMoyenneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $classe, $matter, $cutting, $user;
    public function __construct($classe, $matter, $cutting, $user)
    {
        $this->classe = $classe;
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->user = $user;
    }

    
    public function handle(): void
    {
        $service = app(GestionNoteService::class);
        $students = $service->getStudent($this->classe);
        $classe = $service->classe($this->classe);

        $table = [];
        foreach($students as $i => $item) {
            $notes = $service->getNotEvaluat($item->id, $this->matter, $this->cutting);
            $table[] = [
                'id' => $item->id,
                'genre' => $item->genre,
                'moyen' => $this->moyenne($notes)
            ];
        }

        // Déclenchement de job pour le calcul de moyenne
        MoyenneEditJob::dispatch(
            $table, 
            $this->matter, 
            $this->cutting, 
            $classe,
            $this->user
        );
    }


    private function moyenne($data){
        $total = 0; $coef = 0;
        foreach ($data as $item) {
            if ($item->note !== 'nc') {
                $total += $item->note;
                $coef += $item->value;
            }
        }
        if ($coef === 0) {
            return $total === 0 ? 'nc' : '0.00';
        }
        return sprintf('%05.2f', $total / $coef);
    }
}
