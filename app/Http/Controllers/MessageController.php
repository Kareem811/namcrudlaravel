<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        // Message::create([
        //     'name' => $request->username,
        //     'email' => $request->email,
        //     'message' => $request->message,
        // ]);
        $user = User::where('email', $request->email)->first();
        $request->validate([
            'message' => "regex:/^[a-zA-Z0-9\s]+$/"
        ]);
        Message::create([
            'name' => $request->username,
            'email' => $request->email,
            'message' => $request->message,
            'user_id' => $user ? $user->id : null,
        ]);
        return Response::json("Success", 201);
    }
    public function get()
    {
        return Response::json(Message::all());
    }
    public function reply(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:messages,id',
            'reply' => 'required|string',
        ]);

        $message = Message::find($request->id);
        $message->reply = $request->reply;
        $message->save();

        return response()->json(['msg' => 'Replied successfully']);
    }
}
