<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
<channel>
    <title>ICETECH</title>
    <link>{{ url('/') }}</link>
    <description>კომერციული სამზარეულოს და გასაცივებელი მოწყობილობები საქართველოში</description>
@foreach($products as $product)
    <item>
        <g:id>{{ $product->id }}</g:id>
        <g:title><![CDATA[{{ $product->name }}]]></g:title>
        <g:description><![CDATA[{{ $product->description ? Str::limit(strip_tags($product->description), 5000) : $product->name }}]]></g:description>
        <g:link>{{ route('products.show', $product->slug) }}</g:link>
        <g:image_link>{{ asset('storage/' . $product->image) }}</g:image_link>
        <g:price>{{ number_format($product->price, 2, '.', '') }} GEL</g:price>
        <g:condition>{{ $conditionMap[$product->condition] ?? 'used' }}</g:condition>
        <g:availability>{{ $product->in_stock ? 'in_stock' : 'out_of_stock' }}</g:availability>
        <g:brand>ICETECH</g:brand>
        <g:product_type><![CDATA[{{ $product->category?->name ?? 'კომერციული მოწყობილობები' }}]]></g:product_type>
        <g:identifier_exists>no</g:identifier_exists>
    </item>
@endforeach
</channel>
</rss>
