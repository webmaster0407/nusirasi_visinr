User-agent: *
Disallow:

Sitemap: http://www.numeriubaze.lt/sitemap/codes.xml
Sitemap: http://www.numeriubaze.lt/sitemap/numbers.xml

@foreach($sitemaps as $sitemap)
Sitemap: {{ $sitemap }}
@endforeach