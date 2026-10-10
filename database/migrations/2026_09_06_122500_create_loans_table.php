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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('borrower_id_number');
            $table->string('borrower_name');
            $table->string('study_program');
            $table->string('phone_number');

            $table->string('destination_building');
            $table->string('destination_room');
            $table->text('purpose');
            $table->string('lecturer_name');

            $table->dateTime('requested_at');
            $table->dateTime('expected_return_at');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('returned_at')->nullable();

            $table->enum('status', ['pending', 'borrowed', 'returned', 'rejected', 'expired'])->default('pending');
            $table->enum('return_condition', ['good', 'damaged'])->nullable();
            $table->text('officer_notes')->nullable();

            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
