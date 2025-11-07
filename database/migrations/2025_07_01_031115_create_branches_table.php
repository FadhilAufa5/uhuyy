<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // $table->integer('code')->unique();
            // $table->string('cost_center')->unique();
            // $table->string('name')->unique();
            // $table->string('email')->unique()->nullable();
            // $table->string('manager_name')->nullable();
            // $table->string('admin_head_name')->nullable();
            // $table->string('address')->unique()->nullable();
            // $table->string('phone')->nullable();
            // $table->string('account_name')->nullable();
            // $table->string('account_no')->nullable();
            // $table->boolean('status')->default(true);
            $table->string('file_path')->nullable(); // Path to the PDF file
            $table->enum('status', ['aktif', 'tidak aktif'])->default('tidak aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('branches');
    Schema::enableForeignKeyConstraints();
}



};
