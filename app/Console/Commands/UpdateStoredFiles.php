<?php

namespace App\Console\Commands;

use App\Models\FileUploads;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class UpdateStoredFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:upload-to-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all locally uploaded files to DO Spaces';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = FileUploads::get();

        foreach ($files as $file) {
            $this->info('Current File: ' . $file->id);

            $storedFile = new File(public_path('uploads/' . $file->file));
            $response   = Storage::disk('spaces')->putFile('', $storedFile, 'public');

            $file->link = Storage::disk('spaces')->url($response);
            $file->file = $response;
            $file->save();
        }
    }
}
