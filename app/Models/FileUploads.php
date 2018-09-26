<?php

namespace App\Models;

use App\Helpers\FileValidation;
use App\User;
use getID3;
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

    public function previewImage()
    {
        switch ($this->fileType()) {
            case "image":
                return $this->link;
                break;

            case "video":
                return '/images/video-icon.png';
                break;

            case "audio":
                return '/images/audio-icon.png';
                break;

            case "compressed":
                return '/images/compressed-icon.png';
                break;

            case "text":
                return '/images/text-icon.png';
                break;
        }
    }

    public function dimensions()
    {
        if ($this->fileType() == 'image') {
            return cache()->remember('image-dimensions:' . $this->id, 60 * 24, function () {
                $size = getimagesize($this->link);

                return [
                    'width'  => $size[0],
                    'height' => $size[1],
                ];
            });

        }

        if ($this->fileType() == 'video') {
            return cache()->remember('video-dimensions:' . $this->id, 60 * 24, function () {
                $id      = new getid3;
                $analyze = $id->analyze($this->link);

                return [
                    'width'  => $analyze['video']['resolution_x'],
                    'height' => $analyze['video']['resolution_y'],
                    'fps'    => $analyze['video']['frame_rate'],
                    'length' => $analyze['playtime_string'],
                ];
            });
        }

        return null;
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
