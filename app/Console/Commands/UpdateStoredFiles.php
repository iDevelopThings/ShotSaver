<?php

namespace App\Console\Commands;

use App\Models\FileUploads;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
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
        DB::transaction(function () {
            $files = FileUploads::get();

            $bar = $this->output->createProgressBar($files->count());

            foreach ($files as $file) {
                // $this->info('Current File: ' . $file->id);

                //$fileLink = str_replace('/shotsaver/ShotSaver/', '/shotsaver/', $file->link);
                $fileName = str_replace('/ShotSaver', '', $file->file);

                /*$fileContents = @file_get_contents($file->link);

                if (!$fileContents) {
                    $file->delete();
                    continue;
                }

                $response = Storage::cloud()->put(
                    $fileName,
                    file_get_contents($file->link),
                    'public'
                );*/


                $file->file = $fileName;
                $file->save();

                $bar->advance();
            }

            $bar->finish();
        });


    }
}
