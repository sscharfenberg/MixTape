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
        Schema::create('playlist_entries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->string('path', config('collection.db.songs.path'))->unique();
            $table->string('song', config('collection.db.songs.name'));
            $table->string('artist', config('collection.db.artists.name'));
            $table->string('album', config('collection.db.albums.name'));
            $table->unsignedSmallInteger('sort');
            $table->foreignUuid('playlist_id')
                ->nullable()
                ->constrained()
                ->references('id')
                ->on('playlists')
                ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_entries');
    }
};
