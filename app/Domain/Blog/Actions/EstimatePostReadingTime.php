<?php

namespace Domain\Blog\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class EstimatePostReadingTime
{
    use AsAction;

    /**
     * Calculate the estimated reading time of a post.
     *
     * @param string $text  The post content.
     * @param int $wpm  The words per minute.
     */
    public function handle(string $text, int $wpm = 200): array
    {
        $withoutTags = strip_tags($text);
        $withoutCodeBlocks = preg_replace('/<pre><code.*?<\/code><\/pre>/s', '', $withoutTags);
        $totalWords = str_word_count($withoutCodeBlocks);
        $minutes = floor($totalWords / $wpm);
        $seconds = floor($totalWords % $wpm / ($wpm / 60));

        return [
            'minutes' => $minutes,
            'seconds' => $seconds
        ];
    }
}
