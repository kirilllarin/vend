<?php

namespace App\Services;

use App\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadService
{
    protected $fileRequest;
    protected $name = 'userfile';
    protected $fileName;
    protected $path;
    protected $type;
    protected $parent;
    protected $isImage;

    public function __construct(Model $parent = null, $type)
    {
        $this->parent = $parent;
        $this->type = $type;
    }

    public function upload($request)
    {
        $this->fileRequest = $request->file($this->name);
        $this->path = Storage::put('uploads', $this->fileRequest);
        $this->fileName = basename($this->path);

        // Uhmm
        $this->isImage = explode('/', $this->fileRequest->getClientMimeType())[0] === 'image';

        if($this->isImage) {
            $this->createThumbnail();
        }

        $file = $this->createResource();

        return $file;
    }

    public function createThumbnail()
    {
        $image = Image::make($this->fileRequest)->fit(200, 200)->response();
        Storage::put('uploads/thumbnails/'.$this->fileName, $image->getContent());
    }

    private function createResource()
    {
        $file = new File([
            'name' => $this->fileRequest->getClientOriginalName(),
            'filename' => $this->fileName,
            'size' => $this->fileRequest->getClientSize(),
            'is_image' => $this->isImage,
            'mime_type' => $this->fileRequest->getClientMimeType(),
            'type' => $this->type,
            'parent' => $this->parent ? $this->parent->id : null,
            'path' => $this->path,
        ]);

        if($this->parent) {
            $this->parent->files()->save($file);
        }
        else {
            $file->save();
        }

        return $file;
    }
}