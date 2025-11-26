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
        Schema::create('tracks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->string('name', config('collection.db.tracks.name'));
            $table->unsignedSmallInteger('track')->nullable();
            $table->string('codec', 14)->nullable();
            $table->enum('channel', config('collection.db.tracks.channel'))->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->float('duration', precision: 53)->nullable();
            $table->unsignedMediumInteger('sample_rate')->nullable();
            $table->unsignedMediumInteger('bit_rate')->nullable();
            $table->boolean('vbr')->default(false);
            $table->boolean('cover')->default(false);
            $table->string('path', config('collection.db.tracks.path'))->unique();
            $table->foreignUuid('author_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('authors')
                ->onDelete('cascade');
            $table->foreignUuid('narrator_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('narrators')
                ->onDelete('cascade');
            $table->foreignUuid('audiobook_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('audiobooks')
                ->onDelete('cascade');
            $table->dateTime('modified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
