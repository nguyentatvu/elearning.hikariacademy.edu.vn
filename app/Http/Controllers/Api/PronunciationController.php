<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api;
use App\Services\PronunciationService;

class PronunciationController extends Controller
{
    private $pronunciationService;

    public function __construct(PronunciationService $pronunciationService)
    {
        parent::__construct();
        $this->pronunciationService = $pronunciationService;
    }

    public function test(Request $request)
    {
        $data = $request->only(['name', 'pronunciation_detail_id', 'file']);
        $result = $this->pronunciationService->uploadAudioFileToGetIntonations($data['file'], $data['name'], $data['pronunciation_detail_id']);
        return response()->json($result);
    }
}
