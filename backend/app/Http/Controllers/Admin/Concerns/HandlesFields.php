<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HandlesFields
{
    /**
     * @param  array<int, array<string, mixed>>  $groups
     * @return array<int, array<string, mixed>>
     */
    protected function allFields(array $groups): array
    {
        return collect($groups)
            ->flatMap(fn (array $group): array => $group['fields'])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     */
    protected function mergeCheckboxes(Request $request, array $fields): void
    {
        foreach ($fields as $field) {
            if (($field['type'] ?? 'text') === 'checkbox') {
                $request->merge([$field['name'] => $request->boolean($field['name'])]);
            }
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @return array<string, array<int, mixed>>
     */
    protected function fieldRules(array $fields, ?Model $record = null): array
    {
        $rules = [];

        foreach ($fields as $field) {
            if (empty($field['rules'])) {
                continue;
            }

            $fieldRules = $field['rules'];

            foreach ($fieldRules as $index => $rule) {
                if (! is_string($rule) || ! str_contains($rule, '{id}')) {
                    continue;
                }

                $fieldRules[$index] = $record
                    ? str_replace('{id}', (string) $record->getKey(), $rule)
                    : preg_replace('/,?\{id\}/', '', $rule);
            }

            $rules[$field['name']] = $fieldRules;
        }

        return $rules;
    }

    /**
     * Slug fields are normalised before validation, so a title pasted or browser-
     * autofilled into the slug input can never reach the database as-is.
     *
     * @param  array<int, array<string, mixed>>  $fields
     */
    protected function normalizeSlugInput(Request $request, array $fields): void
    {
        foreach ($fields as $field) {
            if (($field['type'] ?? 'text') !== 'slug') {
                continue;
            }

            $value = $request->input($field['name']);

            if (! is_string($value) || trim($value) === '') {
                continue;
            }

            $request->merge([$field['name'] => Str::slug($value)]);
        }
    }

    /**
     * Convert form strings (tags, gallery rows) into the shapes the models cast.
     *
     * @param  array<int, array<string, mixed>>  $fields
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizeFields(array $fields, array $data): array
    {
        foreach ($fields as $field) {
            $name = $field['name'];
            $type = $field['type'] ?? 'text';
            $value = $data[$name] ?? null;

            if ($type === 'slug' && is_string($value)) {
                $data[$name] = Str::slug($value);
            }

            if ($type === 'checkbox') {
                $data[$name] = (bool) $value;
            }

            if ($type === 'tags') {
                $data[$name] = is_array($value)
                    ? array_values($value)
                    : array_values(array_filter(array_map('trim', explode(',', (string) $value))));
            }

            if ($type === 'gallery') {
                $data[$name] = is_array($value)
                    ? array_values($value)
                    : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $value) ?: [])));
            }

            if (in_array($type, ['date', 'datetime'], true) && blank($value)) {
                $data[$name] = null;
            }

            if ($type === 'number' && blank($value)) {
                $data[$name] = 0;
            }
        }

        return $data;
    }
}
