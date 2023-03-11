@props(['label'=>null, 'name'=>null,'value'=>null])
<div class="form-group">
    <label><?= $label ?></label>
    @php
    $is_invalid = $errors->has($name) ? ' is-invalid':'';
    @endphp
    <input
    name="{{ $name }}"
    value="{{ old($name, $value) }}"
    {{ $attributes->merge([
    'class' => 'form-control form-control-sm' .$is_invalid
    ]) }} />
    @error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>