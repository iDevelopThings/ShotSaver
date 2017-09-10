<?php

namespace App\Console\Commands;

use App\Models\FileUploads;
use Illuminate\Console\Command;

class UpdateSpaceUsed extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'update_space_used';

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
			$upload->size_in_bytes = filesize(url('/uploads/' . $upload->file));
		}
	}
}
