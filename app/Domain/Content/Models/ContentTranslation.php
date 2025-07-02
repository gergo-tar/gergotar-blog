<?php

namespace Domain\Content\Models;

use Domain\Meta\Traits\HasMetas;
use Domain\Translation\Models\AbstractTranslation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Domain\Content\Observers\ContentTranslationObserver;
use Database\Factories\Domain\Content\ContentTranslationFactory;

/**
 * @property int $id Primary key
 * @property string $locale The locale of the translation
 * @property string $content The content in the specified locale
 * @property Content|null $contentModel The content that owns this translation
 */
#[ObservedBy([ContentTranslationObserver::class])]
class ContentTranslation extends AbstractTranslation
{
    use HasMetas;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
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
