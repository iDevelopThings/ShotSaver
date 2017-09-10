<?php

namespace App\Models;

use App\Helpers\FileValidation;
use App\User;
use Illuminate\Database\Eloquent\Model;
use function public_path;

class FileUploads extends Model
{
	protected $guarded = ['id'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function size($type = 'mb')
	{
		switch ($type) {
			case "mb":
				return round($this->size_in_bytes / 1024 / 1024, 2);
				break;
			case "kb":
				return round($this->size_in_bytes / 1024, 2);
				break;
		}

		return round($this->size_in_bytes, 2);
	}

	public function dimensions()
	{
		if ($this->fileType() != 'image') {
			return null;
		}

		$size = getimagesize(public_path() . '/uploads/' . $this->file);

		return [
			'width'  => $size[0],
			'height' => $size[1],
		];
	}

	public function fileType()
	{
		return app(FileValidation::class)->fileType($this->mime_type);
	}

	public function link()
	{
		return route('file', $this->name);
	}
}
