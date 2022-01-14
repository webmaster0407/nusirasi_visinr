<?php

namespace App\Console\Commands\Scrappers;

use App\Models\Number\Number;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class kienonumeris_lt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kienonumeris_lt:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape www.kienonumeris.lt';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $client = new Client();

        $response = $client->request('GET', 'http://www.kienonumeris.lt');
        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        // links array
        $links = $crawler->filterXPath('//a[@class="nr"]/@href')->each(function (Crawler $node, $i) {
            return $node->text();
        });

        $data = [];

        foreach($links as $link) {
            $response = $client->request('GET', $link);
            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);

            $number = $crawler->filterXPath('//h2[@class="ringing"]/a/span/text()')->text();

            //$data[$number]['number'] = $number; // unused
            $data[$number]['view_count'] = $crawler->filterXPath('//p/b[@class="looks"][1]/text()')->text();
            $data[$number]['comment_count'] = $crawler->filterXPath('//p/b[@class="looks"][2]/text()')->text();
            $data[$number]['comments'] = $crawler->filterXPath('//div[@class="comsx"]')->each(function (Crawler $node, $i) {
                $author = $node->filterXPath('//b[1]/i/text()')->text();
                $comment = $node->filterXPath('//span[2]/text()')->text();
                $date = $node->filterXPath('//time/span/text()')->text();

                return [
                    'author' => $author,
                    'comment' => $comment,
                    'date' => $date
                ];
            });
        }

        $this->insert_comment($data);

    }

    private function insert_comment($data)
    {
        //echo count($data) . "\r\n";
        //var_dump($data); die();

        foreach($data as $key => $value) {
            $full_number = $key;

            $short_number = null;
            $full_number = str_replace('+', '', trim($full_number));

            if(substr($full_number, 0, 3) == '370' && strlen($full_number) == 11)
            {
                $short_number = substr($full_number, 3, strlen($full_number));
            }
            elseif (substr($full_number, 0, 1) == '8' && strlen($full_number) == 9)
            {
                $short_number = substr($full_number, 1, strlen($full_number));
            }
            else {
                echo 'bad number: '. $full_number . " skipping...<br />";
                continue; // skip
            }

            // black magic
            try {
                echo $full_number . " black magic<br />";
                $number = Number::firstOrNew(['number' => $short_number]);

                if(!$number->exists) {
                    if($value['view_count'] == 1) {
                        echo $full_number . ' view_count=1 skipping...' . "<br />";
                        continue;
                    }

                    $number->view_count = $value['view_count'];

                    $number->save();
                    echo $full_number . ' insert number' . "<br />";
                }
                /*
                else {
                    // if number exists update view_count
                    if($number->view_count < $value['view_count']) {
                        $number->view_count = $value['view_count'];

                        //$number->save();
                        echo $full_number . " update view_count<br />";
                    }
                }
                */

                // Comments
                if(count($value['comments']) > 0) {
                    foreach($value['comments'] as $comment) {
                        $nc = $number->comments()->firstOrNew([
                            'author' => $comment['author'],
                            'content' => $comment['comment'],
                            'created_at' => $comment['date'] . ' 00:00:00',
                            'updated_at' => $comment['date'] . ' 00:00:00',
                        ]);

                        if($nc->exists) {
                            echo $full_number . ' skipping existing comment' . "<br />";
                            continue;
                        }

                        $nc->save();
                        echo $full_number . ' insert new comment' . "<br />";
                    }
                }
            }
            catch (Exception $e) {
                // log exception
                echo "Exception<br />";
            }

        }
    }
}
