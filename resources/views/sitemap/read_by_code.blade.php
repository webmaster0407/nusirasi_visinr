<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @for($i = 1; $i <= $total_chunks; $i++)
        <url>
            <loc>{{ route('sitemap.readByCodeChunk', ['code' => $code, 'chunk' => $i]) }}</loc>
        </url>
    @endfor
</urlset>