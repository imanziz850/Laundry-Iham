@props(['title'=>[]])
@php
if (isset($title['active'])){
$active = in_array(\Route::currentRouteName(),$title['active']);
} else {
$active = false;
}
@endphp
<li class="nav-item">
    <a class="nav-link{{ $active ? ' active':'' }}" {{ $attributes}}>
        @if (isset($title['icon']))
        <i class="nav-icon {{ $title['icon'] }}"></i>
        @endif
        <p>
            {{ isset($title['name']) ? $title['name'] : '' }}
        </p>
    </a>
</li>