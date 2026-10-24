{{-- Admin form field driven by the field config. Expects $name, $f, $value --}}
@php
    $label = $f['label'] ?? ucfirst(str_replace('_', ' ', $name));
    $id = 'fld-'.$name;
    $type = $f['type'];
    $old = old($name);
    if ($type === 'list') {
        $val = $old ?? (is_array($value) ? implode("\n", $value) : $value);
    } elseif ($type === 'date') {
        $val = $old ?? ($value instanceof \DateTimeInterface ? $value->format('Y-m-d') : $value);
    } elseif ($type === 'datetime') {
        $val = $old ?? ($value instanceof \DateTimeInterface ? $value->format('Y-m-d\TH:i') : $value);
    } else {
        $val = $old ?? $value;
    }
    $max = $f['max'] ?? null;
    $isSeo = in_array($name, ['meta_title', 'meta_description'], true);
@endphp
<div @class(['fld', 'fld-wide' => in_array($type, ['markdown', 'textarea', 'list', 'image']), 'has-err' => $errors->has($name)]) data-type="{{ $type }}">
    @if ($type === 'bool')
        <label class="switch">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" name="{{ $name }}" value="1" @checked($old !== null ? (bool) $old : (bool) $val)>
            <span class="switch-ui" aria-hidden="true"></span>
            <span>{{ $label }}</span>
        </label>
    @else
        <label for="{{ $id }}">{{ $label }} @if($f['required'] ?? false)<i class="req">required</i>@endif</label>
        @switch($type)
            @case('textarea')
                <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $isSeo ? 3 : 5 }}" @if($max) maxlength="{{ $max }}" data-count="{{ $isSeo ? 160 : $max }}" @endif @if($f['readonly'] ?? false) readonly @endif>{{ $val }}</textarea>
                @break
            @case('markdown')
                <div class="md">
                    <div class="md-bar" role="toolbar" aria-label="Formatting">
                        <button type="button" data-md="heading" title="Heading">H</button>
                        <button type="button" data-md="bold" title="Bold"><b>B</b></button>
                        <button type="button" data-md="italic" title="Italic"><i>I</i></button>
                        <button type="button" data-md="list" title="Bullet list">• List</button>
                        <button type="button" data-md="link" title="Link">Link</button>
                        <button type="button" data-md="quote" title="Quote">“ ”</button>
                        <button type="button" class="md-preview-btn" data-md-preview>Preview</button>
                    </div>
                    <textarea id="{{ $id }}" name="{{ $name }}" rows="14" class="md-input">{{ $val }}</textarea>
                    <div class="md-preview prose" hidden></div>
                </div>
                @break
            @case('list')
                <textarea id="{{ $id }}" name="{{ $name }}" rows="6">{{ $val }}</textarea>
                @break
            @case('image')
                <div class="img-field">
                    <div class="img-preview" data-img-preview>
                        @if ($val)<img src="{{ asset($val) }}" alt="">@else<span>No image</span>@endif
                    </div>
                    <div>
                        <input id="{{ $id }}" type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp,image/gif" data-img-input>
                        @if ($val)<label class="check"><input type="checkbox" name="remove_{{ $name }}" value="1"> Remove current image</label>@endif
                    </div>
                </div>
                @break
            @case('select')
                <select id="{{ $id }}" name="{{ $name }}">
                    <option value="">—</option>
                    @foreach ($f['options'] as $k => $v)<option value="{{ $k }}" @selected((string) $val === (string) $k)>{{ $v }}</option>@endforeach
                </select>
                @break
            @case('icon')
                <div class="icon-pick">
                    @foreach (\App\Admin\Resources::ICONS as $k => $v)
                        <label title="{{ $v }}"><input type="radio" name="{{ $name }}" value="{{ $k }}" @checked(($val ?: 'code') === $k)><span>@include('partials.icon', ['name' => $k, 'size' => 22])</span></label>
                    @endforeach
                </div>
                @break
            @case('number')
                <input id="{{ $id }}" type="number" name="{{ $name }}" value="{{ $val }}" min="0">
                @break
            @case('date')
                <input id="{{ $id }}" type="date" name="{{ $name }}" value="{{ $val }}">
                @break
            @case('datetime')
                <input id="{{ $id }}" type="datetime-local" name="{{ $name }}" value="{{ $val }}">
                @break
            @default
                <input id="{{ $id }}" type="{{ in_array($type, ['url', 'email']) ? $type : 'text' }}" name="{{ $name }}" value="{{ $val }}" @if($max ?? false) maxlength="{{ $max }}" @endif @if($isSeo) data-count="60" @endif @if($name === 'slug') data-slug @endif @if($f['readonly'] ?? false) readonly @endif>
        @endswitch
    @endif
    @if (! empty($f['help']))<p class="help">{{ $f['help'] }}</p>@endif
    @error($name)<p class="err">{{ $message }}</p>@enderror
</div>
