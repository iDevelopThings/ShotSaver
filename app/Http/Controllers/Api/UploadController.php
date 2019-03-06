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
     * @throws \GuzzleHttp\Exception\GuzzleException
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

                //We have to wait for the video to finish processing before we can delete it from our server and return a response
                while ($data->percent !== 100) {
                    $data = $api->getVideoInfo($response->shortcode);

                    sleep(1);
                }

                $upload = FileUpload::create([
                    'user_id'       => $user->id,
                    'type'          => $file->getClientOriginalExtension(),
                    'name'          => $response->shortcode,
                    'file'          => $response->shortcode,
                    'mime_type'     => $file->getClientMimeType(),
                    'link'          => Storage::cloud()->url($fileName),
                    'size_in_bytes' => filesize(public_path('/temp/video/' . $fileName)),
                    'info'          => json_encode($data->files->mp4),
                    'thumbnail_url' => $data->thumbnail_url,
                    'embed'         => $data->embed_code,
                    'platform'      => 'streamable',
                ]);

                //Delete the file once streamable has downloaded it
                if ($data->percent === 100) {
                    unlink(public_path('/temp/video/' . $fileName));
                }

                return route('file', $upload->name);
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

                /*if ($fileType === "video") {

                    $tempThumbnailDir = storage_path() . '/app/public/' . str_random() . '.png';

                    if ($output = shell_exec("ffmpeg -i {$file->getRealPath()} -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg {$tempThumbnailDir} 2>&1")) {

                        $thumbnail = Storage::cloud()->putFile('', new \Illuminate\Http\File($tempThumbnailDir),
                            'public');

                        $upload->thumbnail_url = Storage::cloud()->url($thumbnail);
                        $upload->save();

                        unlink($tempThumbnailDir);
                    }
                }*/

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
