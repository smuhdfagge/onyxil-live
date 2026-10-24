<?php

namespace App\Support;

use Illuminate\Support\Str;

class Markdown
{
    /** Render admin-written Markdown. Raw HTML is escaped and unsafe links are dropped. */
    public static function render(?string $text): string
    {
        if (! $text) {
            return '';
        }

        return Str::markdown($text, [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    /** Pull "## " headings out of the body to build a table of contents. */
    public static function headings(?string $text): array
    {
        preg_match_all('/^##\s+(.+)$/m', (string) $text, $m);

        return array_map(fn ($h) => ['text' => trim($h), 'id' => Str::slug($h)], $m[1] ?? []);
    }

    public static function renderWithAnchors(?string $text): string
    {
        $html = static::render($text);

        return preg_replace_callback('/<h2>(.*?)<\/h2>/', fn ($m) => '<h2 id="'.Str::slug(strip_tags(html_entity_decode($m[1]))).'">'.$m[1].'</h2>', $html);
    }
}
