@props(['title'=>[]])
<div class="content-wrapper">
    <div class="content-header">
        @if (isset ($title['name']))
        <div class="container-fluid mb-2">
            <h1 class="m-0">
                @if (isset($title['icon']))
                <i class="{{ $title['icon'] }} mr-2"></i>
                @endif
                {{ $title['name'] }}
            </h1>
        </div>
        @endif
    </div>
    <div class="content">
        <div class="container-fluid">
            <?= $slot ?>
        </div>
    </div>
</div>