<?php

namespace App\Http\Controllers;

use App\Order;
use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    //

    public function __construct() {

    }

    public function index() {
        return view('contact.index');
    }

    public function show() {
        return view('contact.index');
    }

    public function ship(Request $request)
    {
        // $order = Order::findOrFail($orderId);
        $order = "order";

        // Ship order...
        $data = $request->all();
        Mail::to($data['emailaddress'])->send(new OrderShipped($order));
    }
}
