<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Dealer;
use App\Models\Implement;
use App\Models\News;
use App\Models\Tractor;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Allow: /',
            '',
            'Sitemap: '.url('sitemap.xml'),
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }

    public function index(): Response
    {
        $sitemaps = [
            'sitemap-tractors.xml' => Tractor::max('updated_at'),
            'sitemap-implements.xml' => Implement::max('updated_at'),
            'sitemap-dealers.xml' => Dealer::max('updated_at'),
            'sitemap-blogs.xml' => Blog::max('updated_at'),
            'sitemap-news.xml' => News::max('updated_at'),
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($sitemaps as $path => $lastmod) {
            $xml .= '  <sitemap>'."\n";
            $xml .= '    <loc>'.e(url($path)).'</loc>'."\n";
            if ($lastmod) {
                $xml .= '    <lastmod>'.date('c', strtotime($lastmod)).'</lastmod>'."\n";
            }
            $xml .= '  </sitemap>'."\n";
        }
        $xml .= '</sitemapindex>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function tractors(): Response
    {
        $tractors = Tractor::where('status', 'active')->with('brand')->get();

        return $this->urlset($tractors->map(fn ($t) => [
            'loc' => route('tractors.show', [$t->brand, $t]),
            'lastmod' => $t->updated_at,
        ]));
    }

    public function implements(): Response
    {
        $implements = Implement::where('status', 'active')->get();

        return $this->urlset($implements->map(fn ($i) => [
            'loc' => route('implements.show', $i),
            'lastmod' => $i->updated_at,
        ]));
    }

    public function dealers(): Response
    {
        $dealers = Dealer::where('status', 'active')->get();

        return $this->urlset($dealers->map(fn ($d) => [
            'loc' => route('dealers.show', $d),
            'lastmod' => $d->updated_at,
        ]));
    }

    public function blogs(): Response
    {
        $blogs = Blog::where('status', 'published')->get();

        return $this->urlset($blogs->map(fn ($b) => [
            'loc' => route('blog.show', $b),
            'lastmod' => $b->updated_at,
        ]));
    }

    public function news(): Response
    {
        $news = News::where('status', 'published')->get();

        return $this->urlset($news->map(fn ($n) => [
            'loc' => route('news.show', $n),
            'lastmod' => $n->updated_at,
        ]));
    }

    private function urlset($items): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($items as $item) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.e($item['loc']).'</loc>'."\n";
            if ($item['lastmod']) {
                $xml .= '    <lastmod>'.$item['lastmod']->toAtomString().'</lastmod>'."\n";
            }
            $xml .= '  </url>'."\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
