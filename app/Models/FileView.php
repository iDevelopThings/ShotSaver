<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileView extends Model
{

    protected $guarded = ['id'];

    public function file()
    {
        return $this->belongsTo(FileUploads::class);
    }

}
