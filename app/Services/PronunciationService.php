<?php

namespace App\Services;

use App\Repositories\PronunciationRepository;
use Carbon\Carbon;
use CURLFile;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PronunciationService extends BaseService
{
    private $intonationService;

    public function __construct(
        PronunciationRepository $repository,
        IntonationService $intonationService
    ) {
        parent::__construct($repository);
        $this->intonationService = $intonationService;
    }

    /**
     * Upload Audio File to get Intonations
     *
     * @param UploadedFile $file
     * @param string $audioInputName
     * @param int $pronunciationDetailId
     * @return bool
     */
    public function uploadAudioFileToGetIntonations(UploadedFile $file, string $audioInputName, int $pronunciationDetailId)
    {
        $uploadUrl = config('constant.pronunciation.endpoint.upload');
        $fileData = [
            'name' => $audioInputName,
            'file' => new CURLFile(
                $file->getRealPath(),
                $file->getMimeType(),
                $file->getClientOriginalName()
            ),
        ];
        $result = callApi($uploadUrl, 'POST', $fileData);
        $intonationData = [];

        $this->intonationService->deleteByKeyValueConditions([
            'pronunciation_detail_id' => $pronunciationDetailId
        ]);

        foreach ($result['intonations'] as $intonation) {
            $temp = [
                'pronunciation_detail_id' => $pronunciationDetailId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            $temp = array_merge($temp, $intonation);
            $intonationData[] = $temp;
        }

        return $this->intonationService->insert($intonationData);
    }

    public function getIntonation(UploadedFile $audioFile)
    {
        $getIntonationUrl = config('constant.pronunciation.endpoint.get-intonation');
        $mimeType = 'audio/wav';
        $data = [
            'file' => new CURLFile($audioFile->getRealPath(), $mimeType, $audioFile->getClientOriginalName()),
        ];
        $result = callApi($getIntonationUrl, 'POST', $data);

        return $result;
    }
}
