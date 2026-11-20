@props(['items'])

<nav aria-label="Breadcrumb" class="text-sm text-gray-500">
    @foreach($items as $i => $item)
        @if($item['url'] && $i < count($items) - 1)
            <a href="{{ $item['url'] }}" class="hover:underline">{{ $item['name'] }}</a> /
        @else
            <span class="text-gray-800">{{ $item['name'] }}</span>
        @endif
    @endforeach
</nav>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        @foreach($items as $i => $item)
        {
            "@type": "ListItem",
            "position": {{ $i + 1 }},
            "name": {!! json_encode($item['name']) !!}
            @if($item['url']), "item": {!! json_encode($item['url']) !!} @endif
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
