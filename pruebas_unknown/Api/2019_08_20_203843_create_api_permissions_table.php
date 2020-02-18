<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiPermissionsTable extends Migration {
    function __construct(){
        $this->name = config('shinobi.tables.permissions');
    }
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create($this->name, function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name', 50);
			$table->string('slug', 60)->unique();
			$table->boolean('private');
			$table->string('group');
			$table->text('description')->nullable();
			$table->timestamps();
			$table->engine = 'InnoDB';
			$table->charset = 'utf8mb4';
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop($this->name);
	}
}
