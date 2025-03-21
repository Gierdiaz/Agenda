<?php

use App\Enums\{LeadStatusEnum, ServiceTypeEnum};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->string('segment')->nullable();
            $table->json('services', array_column(ServiceTypeEnum::cases(), 'value'))->nullable();
            $table->string('observation')->nullable();
            $table->enum('priority', ['baixa', 'media', 'alta'])->default('media');
            $table->enum('status', array_column(LeadStatusEnum::cases(), 'value'))->default(LeadStatusEnum::NEW ->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
