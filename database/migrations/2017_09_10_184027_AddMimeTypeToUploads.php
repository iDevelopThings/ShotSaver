<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMimeTypeToUploads extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::table('file_uploads', function (Blueprint $table) {
			$table->string('mime_type')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public
	function down()
	{
		Schema::table('file_uploads', function (Blueprint $table) {
			$table->dropColumn('mime_type');
		});
	}
}
