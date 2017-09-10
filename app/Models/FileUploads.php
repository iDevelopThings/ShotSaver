<?php

namespace App\Models;

use App\Helpers\FileValidation;
use Illuminate\Database\Eloquent\Model;

class FileUploads extends Model
{
	protected $guarded = ['id'];

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

	public function fileType()
	{
		return app(FileValidation::class)->fileType($this->mime_type);
	}
}
