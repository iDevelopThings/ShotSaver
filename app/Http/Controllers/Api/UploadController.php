<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileValidation;
use App\Models\FileUploads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Upload a file
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function upload(Request $request)
    {
        $user = Auth::guard('api')->user();

        $file    = $request->file('d');
        $randStr = str_random();

        $fileType = app(FileValidation::class)->fileType($file->getClientMimeType());
        if ($fileType == null) {
            return "This file type is not allowed.";
        }

        if ($fileName = Storage::cloud()->putFile('', $file, 'public')) {

            $upload = FileUploads::create([
                'user_id'       => $user->id,
                'type'          => $file->getClientOriginalExtension(),
                'name'          => $randStr,
                'file'          => $fileName,
                'mime_type'     => $file->getClientMimeType(),
                'link'          => Storage::cloud()->url($fileName),
                'size_in_bytes' => filesize($file->getPathname()),
            ]);

            if ($fileType === "video") {

                $tempThumbnailDir = storage_path() . '/' . str_random() . '.png';

                if ($output = shell_exec("ffmpeg -i {$file->getPath()} -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg {$tempThumbnailDir} 2>&1")) {

                    \Illuminate\Support\Facades\Log::info($output);

                    $thumbnail = Storage::cloud()->putFile('', new \Illuminate\Http\File($tempThumbnailDir), 'public');

                    $upload->thumbnail_url = $thumbnail;
                    $upload->save();

                    unlink($tempThumbnailDir);


                }
            }

            return route('file', $upload->name);
        } else {
            return "Failed to upload file.";
        }
    }

    /**
     * Get the users uploads
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function myUploads(Request $request)
    {
        $user = Auth::guard('api')->user();

        return $user->uploads()->orderBy('id', 'DESC')->paginate(20);
    }
}
