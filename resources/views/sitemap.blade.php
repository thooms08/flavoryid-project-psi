<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach ($staticUrls as $url)
    <url>
        <loc>{{ config('app.url') }}{{ $url['loc'] }}</loc>
        @if(isset($url['lastmod']))
        <lastmod>{{ $url['lastmod'] }}</lastmod>
        @else
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        @endif
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
    </url>
    @endforeach

    @foreach ($events as $event)
    <url>
        <loc>{{ config('app.url') }}/event/{{ $event->slug }}</loc>
        <lastmod>{{ $event->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
