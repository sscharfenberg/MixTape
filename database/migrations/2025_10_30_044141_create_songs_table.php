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
        Schema::create('songs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->string('name', config('collection.db.songs.name'));
            $table->unsignedSmallInteger('track')->nullable();
            $table->unsignedTinyInteger('disc')->nullable();
            $table->string('publisher', config('collection.db.songs.publisher'))->nullable();
            $table->string('composer', config('collection.db.songs.composer'))->nullable();
            $table->string('codec', 14)->nullable();
            $table->enum('channel', config('collection.db.songs.channel'))->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->float('duration', precision: 53)->nullable();
            $table->unsignedMediumInteger('sample_rate')->nullable();
            $table->unsignedMediumInteger('bit_rate')->nullable();
            $table->boolean('vbr')->default(false);
            $table->boolean('cover')->default(false);
            $table->string('path', config('collection.db.songs.path'))->unique();
            $table->foreignUuid('artist_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('artists')
                ->onDelete('cascade');
            $table->foreignUuid('album_artist_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('artists')
                ->onDelete('cascade');
            $table->foreignUuid('album_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('albums')
                ->onDelete('cascade');
            $table->foreignUuid('genre_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('genres')
                ->onDelete('cascade');
            $table->dateTime('modified_at')->nullable();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
