<?php

namespace App\Console\Commands;

use App\Models\FileUploads;
use Illuminate\Console\Command;
use function mime_content_type;

class UpdateMimeTypes extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'update_mime_types';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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

		$uploads = FileUploads::all();
		foreach ($uploads as $upload) {
			$upload->mime_type = mime_content_type(app_path() . '/../public/uploads/' . $upload->file);
			$upload->save();
		}
	}
}
