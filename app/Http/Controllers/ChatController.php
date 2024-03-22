<?php

namespace App\Http\Controllers;

use App\Events\ChatSender;
use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function getRoom(Request $request)
    {
        $friend = User::find($request->friend_id);
        $me = User::find(auth()->user()->id);
        $get_chats = DB::table('chats')->get();
        $chat_lists = array();
        foreach ($get_chats as $chat) {
            if (Crypt::decryptString($chat->user_id) == $me->id && Crypt::decryptString($chat->friend_id) == $friend->id) {
                array_push($chat_lists, [
                    "user_id" => Crypt::decryptString($chat->user_id),
                    "friend_id" => Crypt::decryptString($chat->friend_id),
                    "message" => Crypt::decryptString($chat->message),
                    "status" => $chat->status,
                    "is_read" => $chat->is_read,
                    "created_at" => $chat->created_at,
                    "updated_at" => $chat->updated_at
                ]);
            }
        }
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
            'user_id' => Crypt::encryptString($request->user_id),
            'friend_id' => Crypt::encryptString($request->friend_id),
            'message' => Crypt::encryptString($request->message),
            'is_read' => false,
            'status' => 'sending',
            'created_at' => Carbon::now()
        ]);
        Chat::create([
            'user_id' => Crypt::encryptString($request->friend_id),
            'friend_id' => Crypt::encryptString($request->user_id),
            'message' => Crypt::encryptString($request->message),
            'is_read' => false,
            'status' => 'receive',
            'created_at' => Carbon::now()
        ]);
        return back();
    }
}
