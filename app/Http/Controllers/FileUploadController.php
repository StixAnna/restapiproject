<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use getID3;

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
        $filePath = $file->store('sounds');
        $storagefilePath = Storage::path($filePath);
        // Log::info($storagefilePath);

        $getID3 = new getID3;
        $fileInfo = $getID3->analyze($storagefilePath);
        $duration = round($fileInfo['playtime_seconds']);
        $minutes = floor($duration / 60);
        $seconds = floor($duration % 60);
        $durationFormatted = sprintf('%d:%02d', $minutes, $seconds);

        Sound::create([
            'fname' => $originalName,
            'fsize' => $size,
            'fpath' => $filePath,
            'fduration' => $durationFormatted
        ]);

        // // Invalidate the cache since new data is added
        Redis::del('sounds');

        return response()->json([
            'message' => 'File uploaded successfully',
            'file_name' => $originalName,
            // 'file_size' => $size,
            // 'file_path' => $filePath,
            // 'fduration' => $durationFormatted
        ], 201);
    }

    public function destroy(Sound $sound)
    {
        $filePath = 'sounds/' . basename($sound->fpath);
        // Log::info($filePath);
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
