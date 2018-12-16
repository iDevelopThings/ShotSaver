<?php

namespace App\Console\Commands;

use App\Models\FileUploads;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateStoredFileDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:update-domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update domains on all stored files';

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
            http://151.80.32.59:9000/shotsaver/46dd3cd0537ec6277f7e93a16c307c1a.mp4
            foreach ($files as $file) {

                $link = str_replace('http://151.80.32.59:9000', 'https://storage.idevelopthings.com', $file->link);

                $file->link = $link;

                if($file->thumbnail_url !== null) {
                    $thumnail = str_replace('http://151.80.32.59:9000', 'https://storage.idevelopthings.com', $file->thumbnail_url);
                    $file->thumbnail_url = $thumnail;
                }

                $file->save();

                $bar->advance();
            }

            $bar->finish();
        });


    }
}
