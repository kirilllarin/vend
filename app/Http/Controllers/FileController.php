<?php

namespace App\Http\Controllers;

use App\File;
use App\Services\UploadService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $types
        = [
            'card'    => 'App\Card',
            'message' => 'App\Message',
        ];

    public function __construct()
    {
        $this->middleware('role:editor', ['only' => 'destroy']);
    }

    public function getFile($name)
    {
        return $this->getFileResponse($name);
    }

    public function getThumbnail($name)
    {
        return $this->getFileResponse($name, true);
    }

    public function upload(Request $request)
    {
        $model = null;

        if (
            $request->input('parent')
            && $request->input('type')
            && isset($this->types[$request->input('type')])
        ) {
            $modelBase = app()->make($this->types[$request->input('type')]);
            $model = $modelBase->find($request->input('parent'));
        }

        $uploadService = new UploadService($model, $request->input('type'));
        $file = $uploadService->upload($request);

        return response()->make($file);
    }

    public function destroy(File $file)
    {
        $file->delete();
    }

    private function getFileResponse($name, $thumbnail = false)
    {
        $file = File::where('filename', $name)->first();

        $path = storage_path(sprintf('app/uploads/%s', (
        $thumbnail
            ? 'thumbnails/'.$file->filename
            : $file->filename
        )));

        return response()->file($path, [
            'Content-Type'        => $file->mime_type,
            'Content-Disposition' => 'inline; filename="'.$file->name.'"',
        ]);
    }
}
