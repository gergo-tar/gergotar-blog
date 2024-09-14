<?php

use Domain\Blog\Actions\EstimatePostReadingTime;

test('estimate-post-reading-time', function () {
    $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' .
        'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.' .
        'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.' .
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' .
        'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.';
    $readingTime = EstimatePostReadingTime::run($text);

    $seconds = intval(floor(str_word_count(strip_tags($text)) / 200 * 60));
    expect(intval($readingTime['minutes']))->toBe(0);
    expect(intval($readingTime['seconds']))->toBe($seconds);
});
