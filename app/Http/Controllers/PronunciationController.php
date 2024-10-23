<?php

namespace App\Http\Controllers;

use App\Http\Resources\IntonationResource;
use App\Services\IntonationService;
use App\Services\PronunciationService;
use CURLFile;
use Illuminate\Http\Request;

class PronunciationController extends Controller
{
    private $pronunciationService;
    private $intonationService;

    public function __construct(
        PronunciationService $pronunciationService,
        IntonationService $intonationService
    ) {
        $this->pronunciationService = $pronunciationService;
        $this->intonationService = $intonationService;
    }

    public function assess(Request $request)
    {
        $data = $request->only(['audio_file', 'pronunciation_detail_id']);
        $result = [];
        $userIntonations = $this->pronunciationService->getIntonation($data['audio_file']);
        $sampleIntonations = $this->intonationService->getAllByConditions([
            'pronunciation_detail_id' => $data['pronunciation_detail_id']
        ]);
        $result['user_intonations'] = $userIntonations['intonations'];
        $result['sample_intonations'] = IntonationResource::collection($sampleIntonations);

        return response()->json($result);
    }
}
