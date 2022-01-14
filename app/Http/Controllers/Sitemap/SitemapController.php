<?php

namespace App\Http\Controllers\Sitemap;

use App\Models\Number\Code;
use App\Models\Number\Number;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $codes = Code::where('is_active', 1)
            ->get();

        $sitemaps = [
            'http://www.numeriubaze.lt/sitemap/codes.xml',
            'http://www.numeriubaze.lt/sitemap/numbers.xml'
        ];

        foreach ($codes as $code) {
            $numbers = Number::getNumbersByCode($code->code);
            $chunks = $numbers->chunk(10000);

            for($i = 1; $i <= count($chunks); $i++)
            {
                $sitemaps[] = route('sitemap.readByCodeChunk', ['code' => $code->code, 'chunk' => $i]);
            }
        }

        $content = view('sitemap.index', compact('sitemaps'));
        return \Response::make($content, '200')->header('Content-Type', 'text/xml');
    }

    public function read($section)
    {
        $list = [];

        switch ($section) {
            case 'codes':
                $minutes = 60 * 24; // 1 day
                $items = Cache::remember('sitemap_codes', $minutes, function() {
                    return Code::where('is_active', 1)
                        ->orderBy('id', 'ASC')
                        ->get();
                });

                foreach($items as $item)
                {
                    $list[] = [
                        'loc' => route('number.code.read', [
                            'code' => $item->code
                        ]),
                        'changefreq' => 'always',
                        'priority' => '0.80'
                    ];
                }
                break;
            case 'numbers':
                $minutes = 60 * 24; // 1 day
                $items = Cache::remember('sitemap_numbers', $minutes, function() {
                    return Number::orderBy('updated_at', 'DESC')
                        ->take(10000)
                        ->get();
                });

                foreach($items as $item)
                {
                    $list[] = [
                        'loc' => route('number.number.read', [
                            'number' => '370' . $item->number
                        ]),
                        'changefreq' => 'always',
                        'priority' => '0.80'
                    ];
                }
                break;
            default:
                return redirect('/');
        }

        $content = view('sitemap.read', compact('list'));
        return \Response::make($content, '200')->header('Content-Type', 'text/xml');
    }

    /*
    public function readByCode($code)
    {
        $all_numbers = Number::getNumbersByCode($code);

        $numbers = $all_numbers->chunk(1000);
       //$numbers = $numbers->toArray();

        $total_chunks = count($numbers);

        //$numbers = $numbers[1];

        //dd($numbers);
        //var_dump($numbers); die();

        $content = view('sitemap.read_by_code', compact('code', 'total_chunks'));
        return \Response::make($content, '200')->header('Content-Type', 'text/xml');
    }
    */

    public function readByCodeChunk($code, $chunk, Request $request)
    {
        $c = Code::where('code', $code)->first();

        if(!$c->is_active) {
            //\Log::error('Bad sitemap by code:' . $code . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
            die();
        }

        $all_numbers = Number::getNumbersByCode($code);

        $numbers = $all_numbers->chunk(10000);

        if(!isset($numbers[$chunk-1])) {
            \Log::error('Bad chunk number:' . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
            die();
        }

        $numbers = $numbers[$chunk-1];

        $content = view('sitemap.read_by_code_chunk', compact('numbers'));
        return \Response::make($content, '200')->header('Content-Type', 'text/xml');
    }

    public function robotsGenerator()
    {
        $codes = Code::where('is_active', 1)
            ->get();

        $sitemaps = [];

        foreach ($codes as $code) {
            $numbers = Number::getNumbersByCode($code->code);
            $chunks = $numbers->chunk(10000);

            for($i = 1; $i <= count($chunks); $i++)
            {
                $sitemaps[] = route('sitemap.readByCodeChunk', ['code' => $code->code, 'chunk' => $i]);
            }
        }

        return view('sitemap.robots', compact('sitemaps'));
    }
}
