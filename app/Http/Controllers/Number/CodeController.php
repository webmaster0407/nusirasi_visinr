<?php

namespace App\Http\Controllers\Number;

use App\Models\Number\Code;
use App\Models\Number\Number;
use App\Models\Number\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CodeController extends Controller
{
    public function index() {
        $code1 = Code::where('is_active', 1)->get();
        // select `n`.`number` as `number`, COUNT(number) as cnt, group_concat(c.content) as contents from `numbers_comments` as `c` left join `numbers_numbers` as `n` on `n`.`id` = `c`.`number_id` group by `c`.`number_id` order by `n`.`created_at` desc, `c`.`created_at` desc limit 50
        $code2 = DB::table('numbers_comments as c')
        ->leftjoin('numbers_numbers as n', 'n.id', '=', 'c.number_id')
        ->selectRaw( 'COUNT(n.number) as cnt, c.content as content, n.number as number')
        ->groupBy('c.number_id')
        ->orderBy('n.created_at', 'DESC')
        ->orderBy('c.created_at', 'DESC')
        ->take(50)
        ->get();
        $code2 = json_encode($code2);
        return view('number.code.index', compact('code1', 'code2'));

    }
    public function search(Request $request) {
        $searchkey = $request->input('searchkey');
        // var_dump($searchkey); die;
        $codes = Number::where('number', 'like', '%'.$searchkey.'%')
            ->orderBy('updated_at', 'DESC')->get();
            echo json_encode($codes);
    }

    public function read($code) {
        $code = Code::where('code', $code)
            ->first();

        if(!$code) {
            return redirect()->route('index');
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage() - 1;

        $collection = Number::getNumbersByCode($code->code);

        $perPage = 48;
        if ( $this->isMobileDevice() ) {
            $perPage = 36;
        }
        $currentPageSearchResults = $collection->slice($currentPage * $perPage, $perPage)->all();
        //dd($currentPageSearchResults);

        //$numbers = new Paginator($collection, 50, 2, ['path' => Paginator::resolveCurrentPath()]);
        $paginatedSearchResults = new LengthAwarePaginator($currentPageSearchResults,
            count($collection),
            $perPage,
            $currentPage + 1,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('number.code.read', compact('currentPage'))
            ->withCode($code)
            ->withNumbers($paginatedSearchResults);
    }


    public function isMobileDevice() {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
            return true;
        } else {
            return false;
        }
    }
}
