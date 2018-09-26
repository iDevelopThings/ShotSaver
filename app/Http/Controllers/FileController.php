<?php

namespace App\Http\Controllers;

use App\Models\FileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $file = FileUploads::where('name', $file)->first();
        if (!$file) {
            abort(404);
        }

        return view('upload', [
            'file' => $file,
            'type' => $file->fileType(),
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
        $uploads = $request->user()->uploads()->orderBy('id', 'DESC')->paginate(20);
        $spaceUsed = $request->user()->spaceUsed();

        return view('uploads', [
            'uploads' => $uploads,
            'spaceUsed' => $spaceUsed,
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
        $file = FileUploads::where('name', $file)->where('user_id', Auth::user()->id)->first();
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
        $file = FileUploads::where('name', $file)->where('user_id', Auth::user()->id)->first();
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
        $file = FileUploads::where('name', $file)->where('user_id', Auth::user()->id)->first();
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
        $file = FileUploads::where('name', $file)->where('user_id', Auth::user()->id)->first();
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
}
