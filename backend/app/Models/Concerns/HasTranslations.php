<?php

namespace App\Models\Concerns;

trait HasTranslations
{
    /**
     * Read a bilingual field without forcing every caller to remember whether
     * a model uses `title_id`/`title_en` or the legacy `title`/`title_en`
     * pair. The Indonesian value is always the fallback.
     */
    public function translated(string $field, ?string $locale = null): ?string
    {
        $locale = in_array($locale, ['id', 'en'], true) ? $locale : app()->getLocale();
        $preferred = $locale === 'en' ? '_en' : '_id';
        $fallback = $locale === 'en' ? '_id' : '_en';

        foreach ([$field.$preferred, $field, $field.$fallback] as $attribute) {
            $value = $this->getAttribute($attribute);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return null;
    }
}
