<nav aria-label="breadcrumb" class="icetech-breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}"><i class="bi bi-house-door"></i> მთავარი</a>
        </li>
        @foreach($crumbs as $crumb)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
