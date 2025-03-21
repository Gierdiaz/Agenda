<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('name');
            $table->string('source')->nullable()->comment('Origem do contato (ex: site, indicação, evento)');
            $table->enum('contact_type', ['individual', 'company'])->default('individual');
            $table->string('company_name')->nullable()->comment('Nome da empresa se B2B');
            $table->string('position')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('cep');
            $table->json('address')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
