<?php

use App\Enums\LeadStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->string('source')->nullable();
            $table->string('interest')->nullable();
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
