<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discussion;
use App\Models\DiscussionReply;

class DiscussionController extends Controller
{
    public function index() {
        $discussions = Discussion::with(['user','replies.user'])->latest()->paginate(10);
        return view('discussions.index', compact('discussions'));
    }

    public function store(Request $r) {
        $data = $r->validate([
            'title' => 'required|string|max:150',
            'body'  => 'required|string'
        ]);
        $data['user_id'] = auth()->id();
        Discussion::create($data);
        return back()->with('success','Diskusi dibuat.');
    }

    public function reply(Request $r, Discussion $discussion) {
        $data = $r->validate(['body'=>'required|string']);
        DiscussionReply::create([
            'discussion_id' => $discussion->id,
            'user_id' => auth()->id(),
            'body' => $data['body']
        ]);
        return back()->with('success','Balasan terkirim.');
    }
}
