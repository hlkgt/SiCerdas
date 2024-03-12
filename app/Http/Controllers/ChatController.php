<?php

namespace App\Http\Controllers;

use App\Events\ChatSender;
use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function getRoom(Request $request)
    {
        $friend = User::find($request->friend_id);
        $me = User::find(auth()->user()->id);
        $chat_lists = DB::table('chats')->where([['user_id', $me->id], ['friend_id', $friend->id]])->get();
        return Inertia::render('Chat/ChatRoom', ["friend" => $friend, "me" => $me, "chatLists" => $chat_lists]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            "message" => 'required'
        ]);
        $message = [
            "message" => $request->message,
            "status" => 'receive'
        ];
        event(new ChatSender($message));
        Chat::create([
            'user_id' => $request->user_id,
            'friend_id' => $request->friend_id,
            'message' => $request->message,
            'is_read' => false,
            'status' => 'sending',
            'created_at' => Carbon::now()
        ]);
        Chat::create([
            'user_id' => $request->friend_id,
            'friend_id' => $request->user_id,
            'message' => $request->message,
            'is_read' => false,
            'status' => 'receive',
            'created_at' => Carbon::now()
        ]);
        return back();
    }
}
