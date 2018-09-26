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
            case strstr($mimeType, "application/x-7z-compressed"):
                return "compressed";
                break;
            case strstr($mimeType, "application/x-rar-compressed"):
                return "compressed";
                break;
            case strstr($mimeType, "application/x-gtar"):
                return "compressed";
                break;
            case strstr($mimeType, "application/zip"):
                return "compressed";
                break;
        }

        return null;
    }

}