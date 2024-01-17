<?php

namespace App\Http\Controllers\Message;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('sender', 'recipient')
        ->where(function($message_query){
            $message_query->where('sender_id',auth()->user()->id)
                          ->orWhere('recipient_id',auth()->user()->id);
        })
        ->get();

        return response()->json($messages);
    }

    public function markAsRead(Message $message)
    {
        Message::where('sender_id',$message->sender_id)
                ->where('recipient_id',$message->recipient_id)
                ->update(['is_seen'=>true]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'sender_id'    => 'required',
            'recipient_id' => 'required',
            'content'      => 'required',
        ]);

        $message=Message::create([
            'sender_id'     => $request->sender_id,
            'recipient_id'  => $request->recipient_id,
            'content'       => $request->content
        ]);

        return response()->json(Message::with('sender', 'recipient')->find($message->id), 201);
    }

}
