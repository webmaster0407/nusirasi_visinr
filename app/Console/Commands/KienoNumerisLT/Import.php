<?php

namespace App\Console\Commands\KienoNumerisLT;

use App\Models\Number\Number;
use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kienonumeris:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $file = file_get_contents('C:\laragon\www\visinumeriai\app\Console\Commands\KienoNumerisLT\kn20170227_2.json');
        $data = json_decode($file);

        if(!$data) {
            die('no data');
        }


        foreach($data as $key => $value) {
            $full_number = $value->number;

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
                echo 'bad number: '. $full_number . " skipping...\r\n";
                continue; // skip
            }

            try {
                $number = Number::firstOrNew(['number' => $short_number]);

                if(!$number->exists) {
                    if($value->view_count == 1) {
                        echo $full_number . ' view_count=1 skipping...' . "\r\n";
                        continue;
                    }

                    $number->view_count = $value->view_count;
                    //$number->view_last_ip = $request->ip();

                    $number->save();
                    echo $full_number . ' insert number' . "\r\n";
                }
                else {
                    // if number exists update view_count
                    if($number->view_count < $value->view_count) {
                        $number->view_count = $value->view_count;
                        //$number->save();
                        echo $full_number . " update view_count\r\n";
                    }
                }

                // Comments
                if(count($value->comments) > 0) {
                    // todo: check for duplicates
                    foreach($value->comments as $comment) {
                        $new_comment['author'] = $comment->author;
                        $new_comment['content'] = $comment->comment;
                        $new_comment['created_at'] = $comment->date . ' 00:00:00';
                        $new_comment['updated_at'] = $comment->date . ' 00:00:00';

                        //$number->comments()->create($new_comment);

                        $nc = $number->comments()->firstOrNew([
                            'author' => $comment->author,
                            'content' => $comment->comment,
                            'created_at' => $comment->date . ' 00:00:00',
                            'updated_at' => $comment->date . ' 00:00:00',
                        ]);

                        if($nc->exists) {
                            echo $full_number . ' skipping comment' . "\r\n";
                            continue;
                        }

                        /*
                        $nc = $number->comments()
                            ->where('numbers_comments.author', $comment->author)
                            ->get();

                        if(count($nc) > 0) {
                            echo $full_number . ' skipping comment' . "\r\n";
                            continue;
                        }
                        */

                        $nc->save();
                        echo $full_number . ' insert new comment' . "\r\n";
                    }
                }
            }
            catch (Exception $e) {
                // log exception
                echo "Exception\r\n";
            }

        }

        echo "All Done!\r\n";
    }
}
