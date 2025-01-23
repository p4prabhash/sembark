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
        Schema::create('urls', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('client_id'); 
            $table->unsignedBigInteger('created_by'); 
            $table->text('long_url'); 
            $table->string('short_url', 255); 
            $table->enum('generated_via', ['Manual', 'API'])->default('Manual'); 
            $table->unsignedInteger('hits_success')->default(0); 
            $table->unsignedInteger('hits_failure')->default(0); 
            $table->timestamps(0); 
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
