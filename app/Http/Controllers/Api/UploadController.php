<?php

namespace App\Http\Controllers\Api;

use App\Models\FileUploads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $user = Auth::guard('api')->user();

        $file = $request->file('d');
        $randStr = str_random();
        $fileName = $randStr . '.' . $file->getClientOriginalExtension();
        if ($file->move(public_path() . '/uploads', $fileName)) {
            FileUploads::create([
                'user_id' => $user->id,
                'type'    => $file->getClientOriginalExtension(),
                'name'    => $randStr,
                'file'    => $fileName,
                'link'    => url('/uploads/' . $fileName),
            ]);

            return url('/uploads/' . $fileName);
        } else {
            return "Failed to upload file.";
        }
    }

    public function myUploads(Request $request)
    {
        $user = Auth::guard('api')->user();

        return $user->uploads()->orderBy('id', 'DESC')->paginate(20);
    }
}
