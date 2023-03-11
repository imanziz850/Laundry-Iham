@props(['label'=>null,'name'=>null,'value'=>null])
<div class="form-group">
    <label><?= $label ?></label>
    @php
    $is_invalid = $errors->has($name) ? ' is-invalid':'';
    @endphp
    <textarea
    name="{{ $name }}"
    {{ $attributes->merge([
    'class' => 'form-control form-control-sm'.$is_invalid
    ]) }}><?= old($name, $value) ?></textarea>
    @error($name)
    <div class="invalid-feedback">
    {{ $message }}
    </div>
    @enderror
</div>