<?php

namespace App\Http\Controllers\Api;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{

    /**
     * Return a specific file
     *
     * @param $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function file($file)
    {
        $file = $files = request()->user('api')
            ->uploads()
            ->where('id', $file)
            ->withCount('views')
            ->withCount('favourites')
            ->first();

        if (request()->user('api')->id !== $file->user_id) {
            return response()->json(['message' => 'File does not exist.'], 404);
        }

        $fileResponse = $file;

        $fileResponse->thumbnail_url = $fileResponse->thumbnail(200);
        $fileResponse->uploaded      = $fileResponse->created_at->diffForHumans();
        $fileResponse->size          = $fileResponse->size();
        $fileResponse->type          = ucfirst($fileResponse->fileType());
        $fileResponse->loadingState  = false;

        return response()->json($fileResponse);
    }

    /**
     * Get all of the users files and return a paginated response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function files()
    {
        $files = request()->user('api')
            ->uploads()
            ->withCount('views')
            ->withCount('favourites as total_favourites')
            ->withCount([
                'favourites as favourited' => function ($query) {
                    $query->where('user_id', request()->user('api')->id);
                },
            ])
            ->orderBy('id', 'desc')
            ->paginate(10, request('page', 1));

        if (!$files->first()) {
            return response()->json(['message' => 'You have not uploaded any files yet.'], 500);
        }

        $files->getCollection()->transform(function ($value) {
            return $value->transform();
        });

        return response()->json($files);
    }

    /**
     * Delete a users uploaded file
     *
     * @param FileUpload $file
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(FileUpload $file)
    {
        if (request()->user('api')->id !== $file->user_id) {
            return response()->json(['message' => 'File does not exist.'], 404);
        }

        //TODO: Implement system that deletes the file from the cloud/streamable

        $file->delete();

        return response()->json(['message' => 'Successfully deleted file.']);
    }

    /**
     * Allow any user to favourite this file, so they can come back and view it again in the future
     *
     * @param FileUpload $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function favourite(FileUpload $file)
    {
        return response()->json([
            'favourited'             => $file->favourite('api'),
            'total_favourites_count' => $file->favourites()->count(),
        ]);
    }
}
