{{-- Field form berlabel dengan pesan galat terhubung (aria-describedby). type: text, number, email, tel, date, time, datetime-local, password, file, textarea, select, switch. --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'options' => [], 'help' => null, 'bag' => 'default', 'id' => null])
@php
    $id ??= str_replace(['[', ']', '.'], '-', $name).'-'.$bag;
    $error = $errors->getBag($bag)->first($name);
    $describedBy = trim(($help ? "{$id}-help " : '').($error ? "{$id}-error" : ''));
    $current = $type === 'password' || $type === 'file' ? null : old($name, $value);
@endphp
{{-- mb-3 bawaan hanya bila pemanggil tidak memberi margin bawah sendiri (mb-0 dan mb-3 sama-sama !important). --}}
<div {{ $attributes->only('class')->merge(['class' => str_contains((string) $attributes->get('class'), 'mb-') ? '' : 'mb-3']) }}>
    @if ($type === 'switch')
        <div class="form-check form-switch">
            <input type="hidden" name="{{ $name }}" value="0">
            <input class="form-check-input @if ($error) is-invalid @endif" type="checkbox" role="switch" id="{{ $id }}" name="{{ $name }}" value="1"
                @checked(old($name, $value)) @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
            <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
        </div>
    @else
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
        @if ($type === 'textarea')
            <textarea id="{{ $id }}" name="{{ $name }}" {{ $attributes->except('class')->merge(['class' => 'form-control'.($error ? ' is-invalid' : ''), 'rows' => 3]) }}
                @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>{{ $current }}</textarea>
        @elseif ($type === 'select')
            <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->except('class')->merge(['class' => 'form-select'.($error ? ' is-invalid' : '')]) }}
                @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
                @endforeach
            </select>
        @else
            <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" @if ($current !== null) value="{{ $current }}" @endif
                {{ $attributes->except('class')->merge(['class' => 'form-control'.($error ? ' is-invalid' : '')]) }}
                @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
        @endif
    @endif
    @if ($help)
        <div class="form-text" id="{{ $id }}-help">{{ $help }}</div>
    @endif
    @if ($error)
        <div class="invalid-feedback d-block" id="{{ $id }}-error">{{ $error }}</div>
    @endif
</div>
