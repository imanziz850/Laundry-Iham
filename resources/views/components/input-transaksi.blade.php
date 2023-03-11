@props(['name'=>null,'value'=>null])
@php
$is_invalid = $errors->has($name) ? ' is-invalid ':'';
@endphp
<input
name="{{ $name }}"
value="{{ old($name, $value) }}"
{{ $attributes->merge([
    'class' => 'form-control'.$is_invalid
]) }} />
@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror