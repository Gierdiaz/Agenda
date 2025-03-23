<?php

use App\Enums\{LeadStatusEnum, PriorityEnum, SegmentEnum, ServiceTypeEnum};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->enum('segment', array_column(SegmentEnum::cases(), 'value'))->default(SegmentEnum::TECHNOLOGY->value);
            $table->enum('services', array_column(ServiceTypeEnum::cases(), 'value'))->default(ServiceTypeEnum::SOFTWARE_DEVELOPMENT->value);
            $table->string('observation')->nullable();
            $table->enum('priority', array_column(PriorityEnum::cases(), 'value'))->default(PriorityEnum::LOW->value);
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
