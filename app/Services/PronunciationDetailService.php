<?php

namespace App\Services;

use App\Repositories\PronunciationDetailRepository;
use CURLFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class PronunciationDetailService extends BaseService
{
    public function __construct(PronunciationDetailRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Upload Audio File to get Intonations
     *
     * @param UploadedFile $file
     * @param string $audioInputName
     * @param int $pronunciationDetailId
     * @return bool
     */
    public function uploadAudioFileToGetIntonations(UploadedFile $file, int $pronunciationDetailId)
    {
        $uploadUrl = config('constant.pronunciation.endpoint.upload');
        $token = env('PRONUNCIATION_TOKEN');

        $fileData = [
            'file' => new CURLFile(
                $file->getRealPath(),
                $file->getMimeType(),
                $file->getClientOriginalName()
            ),
        ];

        $result = callApi($uploadUrl, 'POST', $fileData, 'multipart/form-data', $token);

        if ($result) {
            $this->repository->updateOrCreate(
                ['id' => $pronunciationDetailId],
                [
                    'text' => $result['text'],
                    'katakana_text' => $result['katakana_text'],
                    'words' => $result['words']
                ]
            );

            return true;
        }

        return false;
    }

    public function assess(array $data)
    {
        $assessdUrl = config('constant.pronunciation.endpoint.assessment');
        $token = env('PRONUNCIATION_TOKEN');
        $userFile = $data['user_file'];
        $sampleFile = $data['sample_file'];
        $pronunciationDetail = $this->repository->getByConditions(
            ['id' => $data['pronunciation_detail_id']],
            ['text', 'katakana_text', 'words']
        );

        $body = [
            'sample_file' => new CURLFile(
                $sampleFile->getRealPath(),
                $sampleFile->getMimeType(),
                $sampleFile->getClientOriginalName()
            ),
            'user_file' => new CURLFile(
                $userFile->getRealPath(),
                $userFile->getMimeType(),
                $userFile->getClientOriginalName()
            ),
            'sample_data' => json_encode($pronunciationDetail->toArray(), JSON_UNESCAPED_UNICODE),
        ];

        $result = callApi($assessdUrl, 'POST', $body, 'multipart/form-data', $token);

        return $result;
    }
}
