<?php

use App\Enums\ApplicationStatus;
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
        if (Schema::hasTable('applications'))
            return;

        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->integer('application_number');
            $table->date('consultation_date');
            $table->unsignedBigInteger('institution_id');
            $table->unsignedBigInteger('from_institution_id')->nullable();
            $table->unsignedBigInteger('specialist_id')->nullable();
            $table->string('patient_name');
            $table->string('patient_birth_year', 4)->nullable();
            $table->unsignedBigInteger('diagnosis_id');
            $table->enum('status', ApplicationStatus::values())->default(ApplicationStatus::Created->value);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
