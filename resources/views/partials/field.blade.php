{{-- Form field. Expects $name, $label; optional $type, $required, $full, $options, $hint, $value, $autocomplete --}}
@php $type ??= 'text'; $required ??= false; $id = 'f-'.$name; $val = old($name, $value ?? request($name)); @endphp
<div @class(['field', 'full' => $full ?? false, 'has-err' => $errors->has($name)])>
    <label for="{{ $id }}">{{ $label }} @unless($required)<small>(optional)</small>@endunless</label>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" @if($required) required @endif @if(! empty($hint)) placeholder="{{ $hint }}" @endif @error($name) aria-invalid="true" aria-describedby="{{ $id }}-err" @enderror>{{ $val }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" @if($required) required @endif>
            <option value="">Choose one</option>
            @foreach ($options as $o)<option value="{{ $o }}" @selected($val === $o)>{{ $o }}</option>@endforeach
        </select>
    @elseif ($type === 'chips')
        <div class="radio-chips" role="radiogroup" aria-labelledby="{{ $id }}">
            @foreach ($options as $o)
                <label><input type="radio" name="{{ $name }}" value="{{ $o }}" @checked($val === $o)><span>{{ $o }}</span></label>
            @endforeach
        </div>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $val }}" @if($required) required @endif @if(! empty($autocomplete)) autocomplete="{{ $autocomplete }}" @endif @if(! empty($hint)) placeholder="{{ $hint }}" @endif @error($name) aria-invalid="true" aria-describedby="{{ $id }}-err" @enderror>
    @endif
    @error($name)<span class="err" id="{{ $id }}-err">{{ $message }}</span>@enderror
</div>
