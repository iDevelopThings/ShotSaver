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
			'file' => $file,
			'type' => $file->fileType(),
		]);
	}
}
