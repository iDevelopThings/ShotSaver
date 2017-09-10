<?php

namespace App\Http\Controllers;

use App\Models\FileUploads;
use Illuminate\Http\Request;

class FileController extends Controller
{
	public function viewFile($file)
	{
		$file = FileUploads::where('name', $file)->first();
		if (!$file) {
			abort(404);
		}

		return view('upload', [
			'file'       => $file,
			'type'       => $file->fileType(),
			'dimensions' => $file->dimensions(),
		]);
	}

	public function uploads(Request $request)
	{
		$uploads   = $request->user()->uploads()->orderBy('id', 'DESC')->paginate(20);
		$spaceUsed = $request->user()->spaceUsed();

		return view('uploads', [
			'uploads'      => $uploads,
			'spaceUsed'    => $spaceUsed,
			'uploadsCount' => $request->user()->uploads()->count(),
		]);
	}

}
