<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


use App\Member;
use App\Conversation;
use App\Message;
use Carbon\Carbon;

class ChatController extends Controller
{
    // Function to get messages in user screen
    public function getConversationByUserId($id)
    {
        $member = Member::find($id);

        //* mendapatkan percakapan jika tidak akan membuat baru
        // return array []
        $conversation = $member->conversation;
        if ($conversation == null) {
            DB::table('conversations')->insert(
                ["member_id" => $id]
            );
            return response()->json();
        }

        //* mendapatkan semua pesan dalam percakapan
        // di sortir mundur
        if (isset($member->conversation)) {
            $result = $member->conversation->message->sortByDesc('created_at');
            // di ambil hanya valuenya tanpa key pengurut
            return response()->json($result->values()->all());
        }
    }

    // Function to add message
    // Params request and id user
    public function addMessageToConversation(Request $request, $id)
    {
        $member = Member::find($id);
        $convoId = $member->conversation['id'];

        $message = new Message();
        $message->message = $request->message;
        $message->convo_id = $convoId;
        $message->is_admin = $request->is_admin;

        $message->save();
        // if the sender is admin send to target user
        if ($message->is_admin == 1) {
            $res = $this->pushNotifFcm($member->token, "Anda mempunyai pesan baru");
            // if the sender is member send to admin
        } else {
            $res = $this->pushNotifFcm("/topics/admin", "Anda mempunyai pesan baru");
        }


        return response()->json($message);
    }

    public function getAllConversation()
    {
        $conversations =  DB::table('conversations')
            ->join('members', 'members.id', '=', 'member_id')
            ->select('members.id', 'members.nama', 'members.gambar')
            ->get();

        $wadah = array();

        foreach ($conversations as  $conversation) {
            $member = Member::find($conversation->id);
            $messageList = $member->conversation->message->sortByDesc('created_at');
            $messageListSorted = $messageList->values()->all();
            if (isset($messageListSorted[0]->message)) {
                $lastMessage = $messageListSorted[0];
                $time = $lastMessage->created_at;

                // Jika chat terakhir hari ini maka mengembalikan jam : menit
                if ($time->isToday()) {
                    $lastMessageTime = Carbon::parse($time)->format('H:i');
                    // Jika chat terakhir kemarin maka mengembalikan "Kemarin"
                } elseif ($time->isYesterday()) {
                    $lastMessageTime = 'Kemarin';
                    // Else mengembalikan "D:M"
                } else {
                    $lastMessageTime = Carbon::parse($time)->format('y-m-d');
                }
            }

            // push all data above to array
            $conversation->last_message = $lastMessage->message;
            $conversation->last_message_time = $lastMessageTime;
            array_push($wadah, $conversation);
        }

        return response()->json($wadah);
    }

    public function pushNotifFcm($target, $message)
    {
        Http::withHeaders([
            'Authorization' => 'key=AAAAwZJsvEg:APA91bHPeMKaQ0mhXIChaEbV36WnnG7qmc12NJLgWkBBbVd-2LsmUojjlg3KTH1dCcLKLBYKjc1B1U2Ytv5oi-VZRNt4n-MCm5DjbhSL8DWVaI4ene9F0ZDJR5Tm92hpfXOk6j0auNNF',
            'Content-Type' => 'application/json'
        ])->post('https://fcm.googleapis.com/fcm/send', [
            "to" => $target,
            "notification" => array(
                "body" => "Kamera Teman",
                "title" => $message,
            ),
            "data" => array(
                "click_action" => "FLUTTER_NOTIFICATION_CLICK"
            )
        ]);
    }
}
