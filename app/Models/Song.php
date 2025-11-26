<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Song extends Model
{

    use HasUuids;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'songs';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Disable use of timestamps. Since we do a full DB insert, we do not need timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'track',
        'disc',
        'publisher',
        'composer',
        'codec',
        'channel',
        'size',
        'duration',
        'sample_rate',
        'bit_rate',
        'vbr',
        'cover',
        'path',
        'artist_id',
        'album_artist_id',
        'album_id',
        'genre_id',
        'modified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vbr' => 'boolean',
        'cover' => 'boolean',
        'year' => 'integer',
    ];

    /**
     * Get the album associated with the song.
     * @return belongsTo
     */
    public function album(): belongsTo
    {
        return $this->belongsTo(Album::class, 'album_id', 'id');
    }

    /**
     * Get the artist associated with the song.
     * @return belongsTo
     */
    public function artist(): belongsTo
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }

    /**
     * Get the genre associated with the song.
     * @return belongsTo
     */
    public function genre(): belongsTo
    {
        return $this->belongsTo(Genre::class, 'genre_id', 'id');
    }

    /**
     * Get the album artist associated with the song
     * @return belongsTo
     */
    public function albumArtist(): belongsTo
    {
        return $this->belongsTo(Artist::class, 'album_artist_id', 'id');
    }

}
