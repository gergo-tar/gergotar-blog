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
        $withoutCodeBlocks = preg_replace('/<pre><code[\s\S]*?<\/code><\/pre>/m', '', $text);
        $totalWords = str_word_count(strip_tags($withoutCodeBlocks));
        $minutes = floor($totalWords / $wpm);
        $seconds = floor($totalWords % $wpm / ($wpm / 60));

        return [
            'minutes' => $minutes,
            'seconds' => $seconds
        ];
    }
}
