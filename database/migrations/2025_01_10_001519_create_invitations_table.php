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
    Schema::create('invitations', function (Blueprint $table) {
        $table->id(); 
        $table->string('name')->nullable(); 
        $table->string('email')->collation('utf8mb4_unicode_ci'); 
        $table->string('token')->collation('utf8mb4_unicode_ci')->unique(); 
        $table->string('team_id')->nullable(); 
        $table->timestamp('expires_at')->useCurrent()->useCurrentOnUpdate();
        $table->boolean('is_signed_up')->default(false); 
        $table->timestamps(); 
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
