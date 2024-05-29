<?php

namespace Modules\Trello\Services;

use App\Services\CsvService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrelloService
{
    /**
     * @param UploadedFile $file
     * @return true
     */
    public function importCards(UploadedFile $file): true
    {
        $fileName = 'import_' . Carbon::now()->addMonth()->format('mY') . '.csv';
        $storagePath = $file->storeAs('/public/trello', $fileName);
        $filePath = storage_path('app/' . $storagePath);
        $dataCards = CsvService::readCsv($filePath);

        Log::info("Import cards started at " . Carbon::now()->toDateTimeString());
        foreach ($dataCards as $dataCard) {
            $result = $this->createCard($dataCard);
            Log::info($result['id']);
        }
        Log::info("Import cards finished at " . Carbon::now()->toDateTimeString());

        return true;
    }

    /**
     * @param array $card
     * @return array|mixed
     */
    public function createCard(array $card): mixed
    {
        $auth = [
            'key' => Config::get('services.trello.api_key'),
            'token' => Config::get('services.trello.api_token'),
            'idList' => Config::get('services.trello.list_id'),
        ];
        $url = Config::get('services.trello.base_url') . "/cards";

        if (!empty($card['due'])) {
            $card['due'] = Carbon::createFromFormat('d/m/Y H:i', $card['due'])->format('c');
        }

        if (!empty($card['labels'])) {
            $labels = $this->getAllLabels();
            $labelNames = explode(',', $card['labels']);
            foreach ($labelNames as $labelName) {
                if ($labels->where('name', $labelName)->isNotEmpty()) {
                    $card['idLabels'][] = $labels->where('name', $labelName)->first()['id'];
                }
            }
        }

        $query = array_merge($auth, $card);

        try {
            $response = Http::acceptJson()
                ->withQueryParameters($query)
                ->post($url);

            return $response->json();
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * @return Collection|void
     */
    public function getAllLabels()
    {
        // Step 1: Read the JSON file into a string
        $jsonFile = storage_path('app/public/trello/labels.json');
        $jsonString = file_get_contents($jsonFile);

        if ($jsonString === false) {
            die("Error reading the JSON file.");
        }

        // Step 2: Decode the JSON string into a PHP array
        $listCollection = collect(json_decode($jsonString, true));

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Error decoding JSON: " . json_last_error_msg());
        }

        return $listCollection;
    }
}
