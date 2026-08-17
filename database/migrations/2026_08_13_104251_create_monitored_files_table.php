<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitored_files', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename');
            $table->string('stored_path');            // path inside storage/app
            $table->unsignedBigInteger('file_size');   // bytes
            $table->string('mime_type')->nullable();
            $table->string('document_type')->nullable(); // e.g. "leave_request", "policy_doc"
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitored_files');
    }
};