<?php

namespace App\Http\Controllers\Number;

use App\Http\Requests\Number\PostNumberCommentRequest;
use App\Models\Number\Number;
use App\Models\Number\Comment;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(Request $request) {
        $searchkey = $request->input('searchkey');
        // var_dump($searchkey); die;

        $db1 = DB::table('numbers_numbers')->select('*')->take(43)->get();
        $db2 = DB::table('numbers_comments')->select('*')->get();

        $resultArray = [];


        foreach ($db1 as $item) {
            $item = get_object_vars($item);
            $number = $item['number'];
            $numID =$item['id'];
            $numCnt = 0;
            $lastCmt = '';

            foreach ($db2 as $cmt) {
                $cmt = get_object_vars($cmt);
                if ($cmt['number_id'] == $numID){
                    if ($lastCmt == '')
                        $lastCmt = $cmt['content'];
                    $numCnt++;
                }
            }

            $data = [$number, $numCnt, $lastCmt, $numID];
            array_push($resultArray, $data);
        }
        
        $code1 = Comment::orderBy('updated_at', 'DESC')->get()->take(20);
        return view('number.code.lastcomments', compact('code1', 'resultArray'));
            
    }

    public function create($full_number, PostNumberCommentRequest $request)
    {
        $short_number = $full_number;
        if(substr($full_number, 0, 3) == '370' && strlen($full_number) == 11)
        {
            $short_number = substr($full_number, 3, strlen($full_number));
        }
        elseif (substr($full_number, 0, 1) == '8' && strlen($full_number) == 9)
        {
            $short_number = substr($full_number, 1, strlen($full_number));
        }
        else {
            dd('404');
        }

        try {
            $number = Number::firstOrNew(['number' => $short_number]);

            if(!$number->exists) {
                $number->view_last_ip = $request->ip();
                $number->save();
            }

            $comment = $request->input();
            $comment['ip'] = $request->ip();
            $comment['user_agent'] = $request->header('User-Agent');

            $number->comments()->create($comment);

            return redirect()->route('number.number.read', ['number' => $full_number]);
        }
        catch (Exception $e) {
            // log exception
            dd('404');
        }

    }

    /*
     * API
     */

    public function postInsertJson(Request $request) {

        $data = json_decode($request->input('json'));

        if(!$data) {
            dd('4041');
        }

        //dd($data);

        $full_number = $data->number;
        //dd($number);
        $short_number = null;
        $full_number = str_replace('+', '', trim($full_number));
        //dd($short_number);
        if(substr($full_number, 0, 3) == '370' && strlen($full_number) == 11)
        {
            $short_number = substr($full_number, 3, strlen($full_number));
        }
        elseif (substr($full_number, 0, 1) == '8' && strlen($full_number) == 9)
        {
            $short_number = substr($full_number, 1, strlen($full_number));
        }
        else {
            dd('bad number:'. $short_number);
        }

        try {
            $number = Number::firstOrNew(['number' => $short_number]);

            if(!$number->exists) {
                $number->view_count = $data->view_count;
                $number->view_last_ip = $request->ip();
                $number->save();

                foreach($data->comments as $comment) {
                    $new_comment['author'] = $comment->author;
                    $new_comment['content'] = $comment->comment;
                    $new_comment['created_at'] = $comment->date . ' 00:00:00';
                    $new_comment['updated_at'] = $comment->date . ' 00:00:00';

                    $number->comments()->create($new_comment);
                }
            }
            else {
                // check if comment do not exist, insert it
            }
        }
        catch (Exception $e) {
            // log exception
            dd('4043');
        }

        dd('success');
    }

    public function showComment($number) {
        $comments = DB::table('numbers_comments as c')
            ->leftjoin('numbers_numbers as n','n.id','=','c.number_id')
            ->selectRaw( 'c.*, c.created_at as c_created_at, c.updated_at as c_updated_at')
            ->where('n.number', '=', $number)
            ->orderBy('c.id')
            ->get();

        $comments = json_encode($comments);

        return view('number.code.comment_detail', compact('comments', 'number'));


    }
}
