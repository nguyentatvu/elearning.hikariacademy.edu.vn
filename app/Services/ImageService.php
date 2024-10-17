<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageService
{
    private $destinationPath;

    /**
     * Constructor
     */
    public function __construct(?string $destinationPath = null)
    {
        $this->destinationPath = $destinationPath;
    }

    /**
     * Set the destination path
     *
     * @param string $destinationPath
     * @return void
     */
    public function setDestination(string $destinationPath)
    {
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
    public function save(UploadedFile $file, ?string $fileName = null)
    {
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
    public function remove(string $fullFileName)
    {
        if (File::exists($fullFileName)) {
            File::delete($fullFileName);
        }
    }

    /**
     * Remove all images with the same name
     *
     * @param int|string $id
     * @param string $path
     */
    public function removeAllImagesWithTheSameName($id, string $path = '')
    {
        if ($path == '') {
            $path = public_path($this->destinationPath);
        }

        $files = File::glob($path . $id . '.*');
        foreach ($files as $file) {
            File::delete($file);
        }
    }

    /**
     * Upload Image File
     *
     * @param string $name
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImageFile(string $name, UploadedFile $file)
    {
        $filename = $name . '.' . $file->guessClientExtension();
        $this->removeAllImagesWithTheSameName($name);
        $this->save($file, $filename);

        return $filename;
    }
}
