<?php

namespace Domain\Content\Models;

use Domain\Meta\Traits\HasMetas;
use Domain\Translation\Models\AbstractTranslation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Domain\Content\Observers\ContentTranslationObserver;
use Database\Factories\Domain\Content\ContentTranslationFactory;

#[ObservedBy([ContentTranslationObserver::class])]
class ContentTranslation extends AbstractTranslation
{
    use HasMetas;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'locale',
        'content',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ContentTranslationFactory
    {
        return ContentTranslationFactory::new();
    }

    /**
     * Get the content that owns the translation.
     */
    public function contentModel(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}
