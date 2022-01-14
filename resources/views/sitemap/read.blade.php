<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
    </url>
    @foreach($list as $item)
        <url>
            <loc>{{ $item['loc'] }}</loc>
            @if(isset($item['lastmod']))
                <lastmod>{{ $item['lastmod'] }}</lastmod>
            @endif
            @if(isset($item['changefreq']))
                <changefreq>{{ $item['changefreq'] }}</changefreq>
            @endif
            @if(isset($item['priority']))
                <priority>{{ $item['priority'] }}</priority>
            @endif
        </url>
    @endforeach
</urlset>