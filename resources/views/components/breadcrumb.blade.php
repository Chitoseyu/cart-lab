<style>
.breadcrumb-container {
    display: flex;
    align-items: center;
    font-size: 1rem;
}

.breadcrumb-item-link {
    color: #007bff;
    font-weight: bold;
    text-decoration: none;
    transition: color 0.2s ease-in-out;
}

.breadcrumb-item-link:hover {
    color: #0056b3;
}

.breadcrumb-separator {
    width: 18px;
    height: 18px;
    margin: 0 8px;
}

.breadcrumb-item-active {
    font-weight: 600;
    color: #333;
}

</style>
<div class="mb-3">
    <nav aria-label="breadcrumb mb-2">
        <div class="breadcrumb-container bg-white shadow-sm py-3 px-4 rounded-lg d-flex align-items-center">
            @foreach($breadcrumbs as $index => $breadcrumb)
                @if ($index !== array_key_last($breadcrumbs))
                    <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-item-link">{{ $breadcrumb['label'] }}</a>
                    <img src="{{ asset('storage/images/mark_arrow_right.svg') }}" class="breadcrumb-separator">
                @else
                    <span class="breadcrumb-item-active">{{ $breadcrumb['label'] }}</span>
                @endif
            @endforeach
        </div>
    </nav>
</div>


