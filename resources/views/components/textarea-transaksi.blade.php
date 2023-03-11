@props(['name'=>null,'value'=>null])
@php
$is_invalid = $errors->has($name) ? ' is-invalid ':'';
@endphp
<textarea
name="{{ $name }}"
{{ $attributes->merge([
'class' => 'form-control'.$is_invalid
]) }}><?= old($name, $value) ?></textarea>
@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror