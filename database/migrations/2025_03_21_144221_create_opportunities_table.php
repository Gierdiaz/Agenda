<?php

use App\Enums\OpportunityStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->foreignUuid('lead_id')->constrained('leads')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('value', 10, 2)->default(0.00);
            $table->enum('document_type', ['CPF', 'CNPJ'])->nullable();
            $table->enum('status', array_column(OpportunityStatusEnum::cases(), 'value'))->default(OpportunityStatusEnum::ON_HOLD->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
