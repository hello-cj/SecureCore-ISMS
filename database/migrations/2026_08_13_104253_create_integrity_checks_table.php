<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrity_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('monitored_files')->onDelete('cascade');
            $table->foreignId('baseline_hash_id')->nullable()->constrained('file_hashes')->onDelete('set null');
            $table->foreignId('verification_hash_id')->nullable()->constrained('file_hashes')->onDelete('set null');
            $table->enum('status', ['intact', 'modified', 'missing', 'pending'])->default('pending');
            $table->foreignId('checked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('checked_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrity_checks');
    }
};