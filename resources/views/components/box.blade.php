@props(['dataBox'=>[]])
<div class="col-lg-3 col-6">
    <div class="small-box {{ $dataBox['background']}}">
        <div class="inner">
            <h3>{{ $dataBox['value'] }}</h3>
            <p>{{ $dataBox['label'] }}</p>
        </div>
        <div class="icon">
            <i class="{{ $dataBox['icon'] }}"></i>
        </div>
        <a href="{{ $dataBox['href'] }}" class="small-box-footer">
            More info
            <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>