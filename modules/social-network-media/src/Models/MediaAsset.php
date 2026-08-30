<?php

declare(strict_types=1);

namespace Liberu\SocialNetwork\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class MediaAsset extends Model
{
    use SoftDeletes;

    protected $table = 'social_media_assets';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'owner_profile_id', 'album_id', 'type', 'state', 'disk', 'path', 'mime_type', 'size', 'checksum', 'alt_text', 'captions', 'rights', 'metadata'];

    protected function casts(): array
    {
        return ['rights' => 'array', 'metadata' => 'array', 'size' => 'integer'];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
