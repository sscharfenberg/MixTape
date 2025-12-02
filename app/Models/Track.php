<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Narrator;
use App\Models\Author;

class Track extends Model
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
    protected $table = 'tracks';

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
        'codec',
        'channel',
        'size',
        'duration',
        'sample_rate',
        'bit_rate',
        'vbr',
        'cover',
        'path',
        'audiobook_id',
        'author_id',
        'narrator_id',
        'modified_at'
    ];

    /**
     * Get the author associated with this track
     * @return belongsTo
     */
    public function author(): belongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    /**
     * Get the narrator associated with this track
     * @return belongsTo
     */
    public function narrator(): belongsTo
    {
        return $this->belongsTo(Narrator::class, 'narrator_id', 'id');
    }

}
