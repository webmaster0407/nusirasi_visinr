<?php

namespace App\Http\Controllers\Number;

use App\Models\Number\Number;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NumberController extends Controller
{
    public function read($phone_number, Request $request)
    {
        if(substr($phone_number, 0, 3) == '370' && strlen($phone_number) == 11)
        {
            $phone_number = substr($phone_number, 3, strlen($phone_number));
        }
        elseif (substr($phone_number, 0, 1) == '8' && strlen($phone_number) == 9)
        {
            $phone_number = substr($phone_number, 1, strlen($phone_number));
            return redirect()->route('number.number.read', ['number' => '370' . $phone_number], 301);
        }
        else {
            \Log::error('Bad phone number:' . $phone_number . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
            die('naughty boy ...');
        }

        $number = Number::firstOrNew(['number' => $phone_number]);

        if (!$number->exists &&
            !in_array($request->ip(), Config('app_config.admin_ip')) &&
            !preg_match('/bot|crawl|search|slurp|spider|go-http-client/i', $request->header('User-Agent')))
        {
            $number->view_count = 1;
            $number->view_last_ip = $request->ip();
            $number->save();
            \Log::info('Create new phone number:' . $phone_number . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
        }

        if($number->exists &&
            $number->view_last_ip != $request->ip() &&
            !in_array($request->ip(), Config('app_config.admin_ip')) &&
            !preg_match('/bot|crawl|slurp|spider|go-http-client/i', $request->header('User-Agent')))
        {
            $number->view_count += 1;
            $number->view_last_ip = $request->ip();
            $number->save();
            \Log::info('Increment views:' . $phone_number . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
        }

        return view('number.number.read')
            ->withNumber($number);
    }
    public function ajax_read(Request $request)
    {
        $phone_number=$request->input('searchkey');
        if(substr($phone_number, 0, 3) == '370' && strlen($phone_number) == 11)
        {
            $phone_number = substr($phone_number, 3, strlen($phone_number));
        }
        elseif (substr($phone_number, 0, 1) == '8' && strlen($phone_number) == 9)
        {
            $phone_number = substr($phone_number, 1, strlen($phone_number));
            return redirect()->route('number.number.read', ['number' => '370' . $phone_number], 301);
        }
        else {
            \Log::error('Bad phone number:' . $phone_number . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
            die('naughty boy ...');
        }

        $number = Number::firstOrNew(['number' => $phone_number]);

        if (!$number->exists &&
            !in_array($request->ip(), Config('app_config.admin_ip')) &&
            !preg_match('/bot|crawl|search|slurp|spider|go-http-client/i', $request->header('User-Agent')))
        {
            $number->view_count = 1;
            $number->view_last_ip = $request->ip();
            $number->save();
            \Log::info('Create new phone number:' . $phone_number . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
        }

        if($number->exists &&
            $number->view_last_ip != $request->ip() &&
            !in_array($request->ip(), Config('app_config.admin_ip')) &&
            !preg_match('/bot|crawl|slurp|spider|go-http-client/i', $request->header('User-Agent')))
        {
            $number->view_count += 1;
            $number->view_last_ip = $request->ip();
            $number->save();
            \Log::info('Increment views:' . $phone_number . ' IP:' . $request->ip() . ' UserAgent:' . $request->header('User-Agent'));
        }

        return view('number.number.read')
            ->withNumber($number);
    }
}
