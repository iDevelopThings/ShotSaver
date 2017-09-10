<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFileUploadsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_uploads', function (Blueprint $table) {
			$table->bigInteger('size_in_bytes')->default(0);
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
			$table->dropColumn('size_in_bytes');
		});
	}
}
