@php
    $name = $field['name'];
    $type = $field['type'] ?? 'text';
    $wide = (bool) ($field['wide'] ?? false);
    $raw = old($name, $record?->{$name});

    if ($type === 'tags') {
        $value = is_array($raw) ? implode(', ', $raw) : (string) $raw;
    } elseif ($type === 'gallery') {
        if (is_array($raw)) {
            $value = collect($raw)
                ->map(fn ($item) => is_array($item) ? ($item['url'] ?? '') : $item)
                ->filter(fn ($item) => filled($item))
                ->implode("\n");
        } else {
            $value = (string) $raw;
        }
    } elseif ($type === 'datetime') {
        $value = $raw instanceof \DateTimeInterface ? $raw->format('Y-m-d\TH:i') : (string) $raw;
    } elseif ($type === 'date') {
        $value = $raw instanceof \DateTimeInterface ? $raw->format('Y-m-d') : (string) $raw;
    } else {
        $value = $raw === null ? '' : (string) $raw;
    }

    $error = $errors->first($name);
@endphp

<div class="field {{ $wide ? 'field-wide' : '' }}">
    @if ($type === 'checkbox')
        <label class="check" for="field-{{ $name }}">
            <input
                type="checkbox"
                id="field-{{ $name }}"
                name="{{ $name }}"
                value="1"
                @checked(filter_var($raw, FILTER_VALIDATE_BOOLEAN))
            >
            <span>{{ $field['label'] ?? $name }}</span>
        </label>
    @else
        <label for="field-{{ $name }}">{{ $field['label'] ?? $name }}</label>

        @if ($type === 'textarea')
            <textarea
                class="input"
                id="field-{{ $name }}"
                name="{{ $name }}"
                rows="4"
                placeholder="{{ $field['placeholder'] ?? '' }}"
            >{{ $value }}</textarea>
        @elseif ($type === 'richtext')
            <div class="editor" data-editor>
                <div class="editor-toolbar" role="toolbar" aria-label="Formatting">
                    <button type="button" data-format="bold" title="Bold"><strong>B</strong></button>
                    <button type="button" data-format="italic" title="Italic"><em>I</em></button>
                    <button type="button" data-format="h3" title="Heading">H3</button>
                    <button type="button" data-format="p" title="Paragraph">¶</button>
                    <button type="button" data-format="ul" title="Bullet list">• List</button>
                    <button type="button" data-format="link" title="Link">Link</button>
                </div>
                <textarea
                    class="input richtext"
                    id="field-{{ $name }}"
                    name="{{ $name }}"
                    rows="12"
                    spellcheck="false"
                >{{ $value }}</textarea>
            </div>
        @elseif ($type === 'select')
            <select class="input" id="field-{{ $name }}" name="{{ $name }}">
                <option value="">— Select —</option>
                @foreach (($field['options'] ?? []) as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
        @elseif ($type === 'gallery')
            <textarea
                class="input"
                id="field-{{ $name }}"
                name="{{ $name }}"
                rows="5"
                data-gallery
                placeholder="https://raw.githubusercontent.com/..."
            >{{ $value }}</textarea>
            <div class="gallery-preview" data-gallery-preview></div>
        @else
            @php
                $inputType = match ($type) {
                    'email' => 'email',
                    'url' => 'url',
                    'number' => 'number',
                    'date' => 'date',
                    'datetime' => 'datetime-local',
                    default => 'text',
                };
            @endphp
            <input
                class="input"
                type="{{ $inputType }}"
                id="field-{{ $name }}"
                name="{{ $name }}"
                value="{{ $value }}"
                placeholder="{{ $field['placeholder'] ?? '' }}"
                @if ($inputType === 'number') step="1" @endif
            >
        @endif
    @endif

    @if (! empty($field['help']))
        <div class="help">{{ $field['help'] }}</div>
    @endif

    @if ($error)
        <div class="error">{{ $error }}</div>
    @endif
</div>
