<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Проверка наличия файла в запросе
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');

        // Проверка корректности файла
        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload'], 400);
        }

        $originalName = $file->getClientOriginalName();
        $size = $file->getSize();

        // Сохранение файла в директорию sounds
        $path = $file->store('sounds');

        $sound = Sound::create([
            'fname' => $originalName,
            'fsize' => $size,
            'fpath' => $path
        ]);

        // // Invalidate the cache since new data is added
        Redis::del('sounds');

        return response()->json([
            'message' => 'File uploaded successfully',
            'file_name' => $originalName,
            'file_size' => $size,
            'file_path' => $path
        ], 201);
    }

    public function destroy(Sound $sound)
    {
        $filePath = 'sounds/' . basename($sound->fpath);
        Log::info($filePath);
        if (Storage::delete($filePath)) {
            $sound->delete();
            // Invalidate the cache since data is deleted
            Redis::del('sounds');
            return response()->json(null, 204);
        } else {
            return response()->json(['error' => 'Invalid pizdec'], 400);
        }

    }




}
