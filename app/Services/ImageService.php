<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageService {
    private $destinationPath;

    /**
     * Constructor
     */
    public function __construct(?string $destinationPath = null) {
        $this->destinationPath = $destinationPath;
    }

    /**
     * Set the destination path
     *
     * @param string $destinationPath
     * @return void
     */
    public function setDestination(string $destinationPath) {
        $this->destinationPath = $destinationPath;
    }

    /**
     * Save the uploaded file
     *
     * @param UploadedFile $file
     * @param string|null $fileName
     *
     * @return string
     */
    public function save(UploadedFile $file, ?string $fileName = null) {
        if ($fileName == null) {
            $fileName = $file->getClientOriginalName();
        }

        $file->move(public_path($this->destinationPath), $fileName);
        Image::make(public_path($this->destinationPath) . $fileName)->save(public_path($this->destinationPath) . $fileName);

        return $this->destinationPath . $fileName;
    }

    /**
     * Remove the saved file
     *
     * @param string $fullFileName
     */
    public function remove(string $fullFileName) {
        if (File::exists($fullFileName)) {
            File::delete($fullFileName);
        }
    }
}