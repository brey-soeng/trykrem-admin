<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddMenuToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->bigInteger('pid')->after('id')->default(0);
            $table->string('title')->after('name');
            $table->string('icon')->after('title')->nullable();
            $table->string('path')->after('icon')->nullable()->comment('Access path');
            $table->string('component')->after('path')->nullable()->comment('vue Corresponding component address');
            $table->bigInteger('sort')->after('component')->default(1)->comment('Sort');
            $table->tinyInteger('hidden')->after('sort')->default(1)->comment('Whether to hide 0=false|not hide 1=true|hide');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn('pid');
            $table->dropColumn('title');
            $table->dropColumn('icon');
            $table->dropColumn('path');
            $table->dropColumn('component');
            $table->dropColumn('sort');
            $table->dropColumn('hidden');
        });
    }
}
