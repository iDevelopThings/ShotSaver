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
            $fileName = str_random() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('/temp/video'), $fileName);

            $api = new StreamableApi();

            try {
                $response = $api->uploadFileFromUrl(
                    url('/temp/video/' . $fileName)
                );
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                unlink(public_path('/temp/video/' . $fileName));

                return "Failed to process video, reason: {$e->getMessage()}";
            } catch (\Exception $e) {
                unlink(public_path('/temp/video/' . $fileName));

                return "Failed to process video, reason: {$e->getMessage()}";
            }

            if (isset($response->shortcode)) {
                $data = $api->getVideoInfo($response->shortcode);

                //Delete the file once streamable has downloaded it
                if ($data->status === 1) {
                    unlink(public_path('/temp/video/' . $fileName));
                }

                return response()->json($data);

            } else {
                //Remove the file since something must have failed.
                unlink(public_path('/temp/video/' . $fileName));
            }
        } else {
            if ($fileName = Storage::cloud()->putFile('', $file, 'public')) {

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

                        $thumbnail = Storage::cloud()->putFile('', new \Illuminate\Http\File($tempThumbnailDir),
                            'public');

                        $upload->thumbnail_url = Storage::cloud()->url($thumbnail);
                        $upload->save();

                        unlink($tempThumbnailDir);
                    }
                }

                return route('file', $upload->name);
            } else {
                return "Failed to upload file.";
            }
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
