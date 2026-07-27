<?php

namespace App\Jobs\Matters;

use App\Services\GestionNoteService;
use App\Jobs\MoyenneSubMatterJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculSubMoyenneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $matter, $classe, $cutting, $subMatter, $user;
    public function __construct($matter, $classe, $cutting, $subMatter, $user)
    {
        $this->matter = $matter;
        $this->classe = $classe;
        $this->cutting = $cutting;
        $this->subMatter = $subMatter;
        $this->user = $user;
    }


    public function handle(): void
    {
        $service = app(GestionNoteService::class);
        $students = $service->getStudent($this->classe);

        $table = [];
        foreach($students as $i => $item) {
            $notes = $service->getNotEvaluat($item->id, $this->matter, $this->cutting, $this->subMatter);
            $table[] = [
                'id' => $item->id,
                'genre' => $item->genre,
                'moyen' => $this->moyenne($notes)
            ];
        }

        // Déclenchement de job pour le calcul de moyenne
        MoyenneSubMatterJob::dispatch(
            $table,
            $this->matter,
            $this->cutting,
            $this->subMatter,
            $service->classe($this->classe),
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
