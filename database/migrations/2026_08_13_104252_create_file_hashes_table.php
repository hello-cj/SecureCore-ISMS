<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_hashes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('monitored_files')->onDelete('cascade');
            $table->string('algorithm')->default('sha256'); // sha256, md5, sha1
            $table->string('hash_value');
            $table->enum('type', ['baseline', 'verification'])->default('baseline');
            $table->unsignedBigInteger('size_at_hash')->nullable();
            $table->string('permissions_at_hash')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_hashes');
    }
};