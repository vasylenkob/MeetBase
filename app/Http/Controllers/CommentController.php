<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ], [
            'body.required' => 'Коментар не може бути порожнім.',
            'body.max'      => 'Коментар не може перевищувати 1000 символів.',
        ]);

        Comment::create([
            'event_id' => $event->id,
            'user_id'  => auth()->id(),
            'body'     => $request->body,
            'status'   => 'pending',
        ]);

        return back()->with('comment_success', 'Ваш коментар надіслано на модерацію.');
    }
}
