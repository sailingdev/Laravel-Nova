<?php

namespace App\Labs;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;

class FileManager
{
    /**
     * @var
     */
    public $fileWidth;

    /**
     * @var
     */
    public $fileHeight;

    /**
     * @var
     */
    public $extension;
    
    
    /**
     * @param UploadedFile $file
     * @param string $uploadType
     * @param mixed $subDir=null
     * @param mixed $newWidth=null
     * @param mixed $newHeight=null
     * @param mixed $newSize=null
     * 
     * @return array
     */
    public function uploadFile(UploadedFile $file, string $uploadType, $subDir=null, $newWidth=null, $newHeight=null, $newSize=null, $disk='public'): array 
    {     
        
        $fileProps = getimagesize($file);
       
        $this->fileWidth = $fileProps[0];
        $this->fileHeight = $fileProps[1]; 
    
        $this->extension = strtolower($file->extension());
        
        $subDirPath = $subDir != null ? $subDir. '/' : '';

        $newFileName = $this->createNewFileName($uploadType);
        try {
            if ($newWidth !== null || $newHeight !== null) { 
                 
                $resizeStandardSize = Image::make($file->getRealPath())
                ->resize($newWidth, $newHeight, function($constraint) {
                    $constraint->aspectRatio();
                })->stream(); 
                Storage::disk($disk)->put($subDirPath . $newFileName, $resizeStandardSize); 
            } 
            else { 
                $stream = Image::make($file->getRealPath())->stream(); 
                Storage::disk($disk)->put($subDirPath . $newFileName, $stream); 
            } 
            return [true, $newFileName];
        } catch (\Throwable $th) {
           return [false, $th->getMessage()];
        }  
    }

    
    /**
     * Fetch the path of a remote file
     * 
     * @param string $fileName
     * @param string $subPath The subPath of the file (e.g passport, signatures, etc)
     * @return string remote The file path or a default file
     */
    public static function fetchUploadedFilePath($fileName=null, string $subPath, $disk='public'): ?string 
    {      
        return 
        $fileName !== null && Storage::disk($disk)->exists($subPath . '/' . $fileName) ? 
        Storage::disk($disk)->url($subPath . '/' . $fileName) : null;
    }

    /**
     * @param string $uploadType
     * 
     * @return string
     */
    private function createNewFileName(string $uploadType): string
    { 
        $sm = new StringManipulator; 
        return $sm->getAlphaNum(md5(Str::orderedUuid())) . time() .'.'. $this->extension;
    }

    /**
     * @return array
     */
    protected function uploadGroups()
    {
        return [
            'user' => ['profile_photo', 'cover_photo'],
            'management' => ['admin', 'clog']
        ];
    }

    /**
     * @param string $uploadType
     * 
     * @return string|null
     */
    protected function getUploadGroup(string $uploadType): ?string
    {
        $group = array_filter($this->uploadGroups(), function ($g) use ($uploadType) {
            return in_array($uploadType, $g);
        });
        if (count($group) > 0) {
            return array_keys($group)[0];
        }
        return null;
    }
}