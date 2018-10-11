<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\User;
use Illuminate\Http\Request;

class AcceptFriendshipsController extends Controller
{

    public function index() {

        return view('friendships.index', [

            'friendshipRequests' => Friendship::with('sender')->where([

                'recipient_id' => auth()->id()

            ])->get()

        ]);

    }

    public function store(User $sender) {

        Friendship::where([

            'sender_id' => $sender->id,
            'recipient_id' => auth()->id(),

        ])->update(['status' => 'accepted']);

    }

    public function destroy(User $sender) {

        Friendship::where([

            'sender_id' => $sender->id,
            'recipient_id' => auth()->id(),

        ])->update(['status' => 'denied']);

    }
}
