<?php

namespace Domain\Blog\Actions;

use DOMDocument;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateTocForContent
{
    use AsAction;

    /**
     * Generate a Table of Contents (ToC) for the given content.
     *
     * @param  mixed  $content  The content to generate ToC for. HTML or JSON.
     */
    public function handle(mixed $content): ?array
    {
        // Check if the content is JSON
        if (is_array($content)) {
            return $this->generateTocFromJson($content);
        }

        return $this->generateTocFromHtml($content);
    }

    /**
     * Update the content to add IDs to <h3> tags if missing, ensuring unique IDs.
     * And generate a Table of Contents (ToC) from the given HTML content.
     *
     * @param string $html  The HTML content.
     */
    protected function generateTocFromHtml(string $html): array
    {
        $dom = new DOMDocument();
        // In case of video embeds, we need to ignore the errors.
        libxml_use_internal_errors(true);

        $dom->loadHTML($html);
        $toc = '';
        $currentH3Id = null;

        // Find all <h3> and <h4> elements
        foreach ($dom->getElementsByTagName('*') as $index => $element) {
            if ($element->tagName === 'h3' || $element->tagName === 'h4') {
                // Check if the element has an ID
                $id = $element->getAttribute('id');

                // If no ID exists, generate a unique ID and set it
                if (!$id) {
                    $id = $element->tagName . '-' . $index . '-' . uniqid();
                    $element->setAttribute('id', $id);  // Set the unique ID
                }

                if ($element->tagName === 'h3') {
                    $currentH3Id = $id;
                    $toc .= '<li><a href="#' . $id . '">' . $element->textContent . '</a></li>';
                } elseif ($element->tagName === 'h4' && $currentH3Id) {
                    $toc .= '<ul><li><a href="#' . $id . '">' . $element->textContent . '</a></li></ul>';
                }
            }
        }

        // Save and return the updated HTML content but only the body
        $body = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));

        // Remove <body> and </body> tags
        return [
            'content' => str_replace(['<body>', '</body>'], '', $body),
            'toc' => $toc !== ''
                ? "<ul>{$toc}</ul>"
                : null,
        ];
    }

    /**
     * Generate the Table of Contents (ToC) for the Json content.
     *
     * @param  array  $jsonContent The JSON content.
     */
    protected function generateTocFromJson(array $jsonContent): array
    {
        $toc = '';
        $currentH3Id = null;

        // Initialize index for generating unique IDs
        $index = 1;

        // Start traversing the JSON content to generate the ToC
        $this->traverseJsonContent($jsonContent['content'], $toc, 1, $index, $currentH3Id);

        // Return the updated JSON with IDs and the generated ToC
        return [
            'content' => $jsonContent,
            'toc' => $toc !== ''
                ? "<ul>{$toc}</ul>"
                : null,
        ];
    }

    /**
     * Helper function to traverse the content array and build ToC.
     *
     * @param  array  $content  The content array to traverse.
     * @param  string  $toc  The ToC string.
     * @param  int  $level  The current heading level.
     * @param  int  $index  The index of the block.
     * @param  string|null  $currentH3Id  The current H3 ID.
     */
    protected function traverseJsonContent(
        array &$content,
        string &$toc,
        int $level = 1,
        int &$index = 1,
        ?string &$currentH3Id = null
    ): void {
        foreach ($content as $i => $block) {
            // Check if the block is a heading (h3 or h4)
            if ($block['type'] === 'heading' && isset($block['attrs']['level'])) {
                $this->processHeading($block, $toc, $index, $currentH3Id);
                $content[$i] = $block;  // Update the block in the content array
            }

            // Recursively traverse nested content (if exists)
            if (isset($block['content']) && is_array($block['content'])) {
                $this->traverseJsonContent($block['content'], $toc, $level + 1, $index, $currentH3Id);
            }
        }
    }

    /**
     * Helper function to process heading blocks.
     *
     * @param  array  $block  The block to process.
     * @param  string  $toc  The ToC string.
     * @param  int  $index  The index of the block.
     * @param  string|null  $currentH3Id  The current H3 ID.
     */
    protected function processHeading(array &$block, string &$toc, int &$index, ?string &$currentH3Id = null): void
    {
        $headingLevel = $block['attrs']['level'];
        $textContent = isset($block['content'][0]['text']) ? $block['content'][0]['text'] : '';
        $slug = Str::slug($textContent);

        // Generate or retrieve the block's ID
        $id = $this->generateId($block, $index, $slug);

        // Generate ToC based on heading level
        if ($headingLevel == 3) {
            $currentH3Id = $id;
            $toc .= '<li><a href="#' . $id . '">' . $textContent . '</a></li>';
        } elseif ($headingLevel == 4 && $currentH3Id) {
            $toc .= '<ul><li><a href="#' . $id . '">' . $textContent . '</a></li></ul>';
        }

        $index++;  // Increment index to ensure unique IDs
    }

    /**
     * Helper function to generate unique IDs for headings.
     *
     * @param  array  $block  The block to generate an ID for.
     * @param  int  $index  The index of the block.
     */
    protected function generateId(array &$block, int $index, string $slug): string
    {
        // Check if there's an existing ID, otherwise generate one
        $id = $block['attrs']['id'] ?? null;
        if (!$id) {
            $id = $index . '-' . $slug;
            $block['attrs']['id'] = $id;  // Add the unique ID to the block
        }

        return $id;
    }
}
