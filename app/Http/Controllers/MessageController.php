<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        Message::create([
            'name' => $request->username,
            'email' => $request->email,
            'message' => $request->message,
        ]);
        return Response::json("Success", 201);
    }
    public function get()
    {
        return Response::json(Message::all());
    }
}
