<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Http\Requests\StoreDiscussionRequest;
use App\Http\Requests\ReplyDiscussionRequest;

class DiscussionController extends Controller
{
public function index(Request $request)
    {
        $q = $request->query('q');

        $discussions = Discussion::with(['user', 'replies.user'])
            ->when($q, function ($query) use ($q) {
                // Bungkus pakai closure agar orWhere hanya berlaku di blok pencarian
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('body', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10);

        // Pertahankan ?q= saat pindah halaman (tanpa memicu warning IDE)
        $discussions->appends($request->query());

        return view('discussions.index', [
            'discussions' => $discussions,
            'q'           => $q,
        ]);
    }


    public function store(StoreDiscussionRequest $r) {
        Discussion::create($r->validated() + ['user_id'=>auth()->id()]);
        return back()->with('success','Diskusi dibuat.');
    }

    public function reply(ReplyDiscussionRequest $r, Discussion $discussion) {
        DiscussionReply::create([
            'discussion_id'=>$discussion->id,
            'user_id'=>auth()->id(),
            'body'=>$r->validated()['body']
        ]);
        return back()->with('success','Balasan terkirim.');
    }
}
