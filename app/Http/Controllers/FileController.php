<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Aws\S3\Exception\S3Exception;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function in_array;
use League\Flysystem\FileNotFoundException;

class FileController extends Controller
{

    /**
     * View a file on the website
     *
     * @param $file
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewFile($file)
    {
        $file = FileUpload::where('name', $file)->first();

        if (!$file) {
            abort(404);
        }

        $file->saveView();

        return view('upload', [
            'file'       => $file,
            'type'       => $file->fileType(),
            'dimensions' => $file->dimensions(),
        ]);
    }

    public function viewFileTwitter($file)
    {
        $file = FileUpload::where('name', $file)->first();

        if (!$file) {
            abort(404);
        }

        $file->saveView();

        return view('upload-twitter', [
            'file'       => $file,
            'type'       => $file->fileType(),
            'dimensions' => $file->dimensions(),
        ]);
    }

    /**
     * View my uploads
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploads(Request $request)
    {
        //Its helpful to make sure we always know what page we're on
        /*if (!$request->has('page')) {
            return redirect()->route('my-uploads', ['page' => 1]);
        }*/


        /*$uploads = $request->user()
            ->uploads();

        $filters = ['created_at', 'size_in_bytes', 'mime_type'];
        $orders  = ['desc', 'asc'];
        $filter  = 'id';
        $order   = 'desc';

        if ($request->has('filter_by') && in_array($request->filter_by, $filters)) {
            $filter = $request->filter_by;
        }
        if ($request->has('order') && in_array($request->order, $orders)) {
            $order = $request->order;
        }

        $uploads = $uploads->orderBy($filter, $order)
            ->paginate(20);*/


        $spaceUsed = $request->user()->spaceUsed();

        return view('uploads', [
            //'uploads'      => $uploads,
            'spaceUsed'    => $spaceUsed,
            'uploadsCount' => $request->user()->uploads()->count(),
        ]);
    }

    /**
     * Add a description to a file
     *
     * @param Request $request
     * @param         $file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addFileDescription(Request $request, $file)
    {
        $file = FileUpload::where('name', $file)->where('user_id', Auth::user()->id)->first();
        if (!$file) {
            abort(404);
        }
        if ($request->has('description')) {
            $file->description = $request->description;
            $file->save();
            $request->session()->flash('success', 'File description added');

            return redirect()->back();
        }
        $request->session()->flash('failure', 'Failed to add description');

        return redirect()->back();
    }

    /**
     * Begin editing a file description
     *
     * @param Request $request
     * @param         $file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function viewEditDescription(Request $request, $file)
    {
        $file = FileUpload::where('name', $file)->where('user_id', Auth::user()->id)->first();
        if (!$file) {
            abort(404);
        }
        $request->session()->flash('edit_description', '');

        return redirect()->back();
    }

    /**
     * Save a new file description
     *
     * @param Request $request
     * @param         $file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editDescription(Request $request, $file)
    {
        $file = FileUpload::where('name', $file)->where('user_id', Auth::user()->id)->first();
        if (!$file) {
            abort(404);
        }
        if ($request->has('description')) {
            $file->description = $request->description;
            $file->save();
            $request->session()->flash('success', 'File description updated');

            return redirect()->back();
        }
        $request->session()->flash('failure', 'Failed to update description');

        return redirect()->back();
    }

    /**
     * Remove a file description
     *
     * @param Request $request
     * @param         $file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeDescription(Request $request, $file)
    {
        $file = FileUpload::where('name', $file)->where('user_id', Auth::user()->id)->first();
        if (!$file) {
            abort(404);
        }
        $file->description = null;
        if ($file->save()) {
            $request->session()->flash('success', 'File description removed');

            return redirect()->back();
        }
        $request->session()->flash('failure', 'Failed to remove description');

        return redirect()->back();
    }

    /**
     * Favourite a file
     *
     * @param Request $request
     * @param         $file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function favourite(Request $request, $file)
    {
        $file = FileUpload::where('name', $file)->where('user_id', Auth::user()->id)->first();
        if (!$file) {
            abort(404);
        }

        $file->favourite();

        return redirect()->back();
    }

    /**
     * Allow the user to view all their favourites
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function favourites(Request $request)
    {
        $favourites = auth()->user()->favourites()->with('favourable')->paginate(20);

        return view('favourites', [
            'favourites' => $favourites,
        ]);
    }

    /**
     * Allow the user to delete a file
     *
     * @param Request $request
     * @param         $file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $file)
    {
        $file = FileUpload::where('name', $file)->where('user_id', Auth::user()->id)->first();
        if (!$file) {
            abort(404);
        }
        Storage::disk('spaces')->delete($file->file);
        $file->delete();

        session()->flash('success', 'Successfully deleted file.');

        return redirect(route('my-uploads', ['page' => request('page', 1)]));
    }
}
