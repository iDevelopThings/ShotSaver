<?php
/**
 * Created by PhpStorm.
 * User: Sam8t
 * Date: 10/09/2017
 * Time: 7:36 PM
 */

namespace App\Helpers;


class FileValidation
{

	public function fileType($mimeType)
	{
		switch ($mimeType) {
			case strstr($mimeType, "video/"):
				return "video";
				break;
			case strstr($mimeType, "image/"):
				return "image";
				break;
			case strstr($mimeType, "audio/"):
				return "audio";
				break;
			case strstr($mimeType, "text/"):
				return "text";
				break;
		}

		return null;
	}

}