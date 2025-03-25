<?php

use App\Enums\{LeadStatusEnum, PriorityEnum, SegmentEnum, ServiceTypeEnum};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            // Identificação do lead
            $table->uuid('id')->primary()->unique();
            $table->string('name');
            $table->enum('lead_type', ['individual', 'company'])->default('individual');
            $table->string('phone');
            $table->string('email');

            // Informações sobre a empresa (se B2B)
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();

            // Interesses e necessidades
            $table->enum('segment', array_column(SegmentEnum::cases(), 'value'));
            $table->enum('services', array_column(ServiceTypeEnum::cases(), 'value'));
            $table->json('interests')->nullable();
            $table->text('observation')->nullable();

            // Origem do lead
            $table->string('source')->nullable();
            $table->text('lead_source_details')->nullable();

            // Status e acompanhamento
            $table->enum('priority', array_column(PriorityEnum::cases(), 'value'));
            $table->enum('status', array_column(LeadStatusEnum::cases(), 'value'));

            // Outros detalhes e metadados
            $table->string('cep');
            $table->json('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
