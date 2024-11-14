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
    public function __construct(
        PronunciationRepository $repository
    ) {
        parent::__construct($repository);
    }
}
