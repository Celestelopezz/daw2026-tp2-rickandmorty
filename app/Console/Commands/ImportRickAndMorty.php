<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Character;
use App\Models\Episode;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportRickAndMorty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rickandmorty:import';

    private const API = 'https://rickandmortyapi.com/api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa locations, episodes y characters desde The Rick and Morty API';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->importLocations();
        $this->importEpisodes();
        $this->importCharacters();

        $this->info('Importación finalizada.');

        return self::SUCCESS;
    }
    private function importLocations(): void
    {
        $this->info('Importando locations...');

        $this->eachPage('location', function (array $l) {
            Location::updateOrCreate(  // si existe un registro con ese id, lo actualiza, si no, lo crea.
                ['id' => $l['id']],
                [
                    'name' => $l['name'],
                    'type' => $l['type'],
                    'dimension' => $l['dimension'],
                ]
            );
        });
    }

    private function importEpisodes(): void
    {
        $this->info('Importando episodes...');

        $this->eachPage('episode', function (array $e) {
            Episode::updateOrCreate(
                ['id' => $e['id']],
                [
                    'name' => $e['name'],
                    'air_date' => $e['air_date'],
                    'episode' => $e['episode'],
                    'created' => $e['created'],
                ]
            );
        });
    }

    private function importCharacters(): void
    {
        $this->info('Importando characters...');

        $this->eachPage('character', function (array $c) {
            $locationId = ! empty($c['location']['url'])
                ? (int) basename($c['location']['url']) // de aca saco el id
                : null;

            $character = Character::updateOrCreate(
                ['id' => $c['id']],
                [
                    'name' => $c['name'],
                    'status' => $c['status'],
                    'species' => $c['species'],
                    'type' => $c['type'],
                    'gender' => $c['gender'],
                    'image' => $c['image'] ?? null,
                    'origin' => $c['origin']['name'] ?? null,
                    'location_id' => $locationId,
                ]
            );

            $episodeIds = collect($c['episode'])
                ->map(fn($url) => (int) basename($url)) // de aca saco el id
                ->all();

            $character->episodes()->syncWithoutDetaching($episodeIds); // agrega los episodios del personaje sin duplicar
        });
    }


    private function eachPage(string $resource, callable $callback): void
    {
        $url = self::API . '/' . $resource;

        while ($url) {
            $response = Http::retry( // hasta 5 intentos, esperando 3 segundos entre cada uno
                5,
                3000,
                fn($exception) => $exception instanceof \Illuminate\Http\Client\RequestException
                    && $exception->response->status() === 429,
                throw: false // evita que la excepción corte el comando
            )->get($url);

            if ($response->failed()) {
                $this->error("Fallo la consulta a $url (status {$response->status()})");
                return;
            }

            $data = $response->json();

            DB::transaction(function () use ($data, $callback) {
                foreach ($data['results'] as $item) {
                    $callback($item);
                }
            });

            $this->line(" - página lista: $url");

            $url = $data['info']['next'];

            usleep(500000); // pausa de 1/2 segundo entre páginas
        }
    }
}
