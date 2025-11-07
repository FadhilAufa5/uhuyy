<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('branches', function (Blueprint $table) {
    if (!Schema::hasColumn('branches', 'status')) {
        $table->enum('status', ['aktif', 'tidak aktif'])->default('tidak aktif');
    }
});

}

public function down()
{
    Schema::table('branches', function (Blueprint $table) {
         $table->dropColumn('status');
    });
}

};
