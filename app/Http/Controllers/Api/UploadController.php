<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileValidation;
use App\Models\FileUpload;
use App\Support\Streamable\StreamableApi;
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

        if ($fileType === "video") {

            $response = [];


            $fileName = str_random() . '.'.$file->getClientOriginalExtension();

            $movedFile = $file->move(public_path('/temp/video'), $fileName);

            $api = new StreamableApi();


            $response = $api->uploadFileFromUrl(
                url('/temp/video/' . $fileName)
            );

            if (isset($response->shortcode)) {
                $data = $api->getVideoInfo($response->shortcode);

                return response()->json($data);

            }


            return response()->json($response);

            return "done";

        }

        /* if ($fileName = Storage::cloud()->putFile('', $file, 'public')) {

             $upload = FileUpload::create([
                 'user_id'       => $user->id,
                 'type'          => $file->getClientOriginalExtension(),
                 'name'          => $randStr,
                 'file'          => $fileName,
                 'mime_type'     => $file->getClientMimeType(),
                 'link'          => Storage::cloud()->url($fileName),
                 'size_in_bytes' => filesize($file->getPathname()),
             ]);

             if ($fileType === "video") {

                 $tempThumbnailDir = storage_path() . '/app/public/' . str_random() . '.png';

                 if ($output = shell_exec("ffmpeg -i {$file->getRealPath()} -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg {$tempThumbnailDir} 2>&1")) {

                     $thumbnail = Storage::cloud()->putFile('', new \Illuminate\Http\File($tempThumbnailDir), 'public');

                     $upload->thumbnail_url = Storage::cloud()->url($thumbnail);
                     $upload->save();

                     unlink($tempThumbnailDir);
                 }
             }

             return route('file', $upload->name);
         } else {
             return "Failed to upload file.";
         }*/
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
