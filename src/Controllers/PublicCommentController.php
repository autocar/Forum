<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Thread;
use Laralum\Forum\Models\Comment;
use Illuminate\Support\Facades\Auth;

class PublicCommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laralum\Forum\Models\Thread  $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Thread $thread)
    {
        $this->authorize('publicCreate', Comment::class);

        $this->validate($request, [
            'comment' => 'required|max:500',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'thread_id' => $thread->id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('laralum_public::forum.threads.show', ['thread' => $thread->id])
            ->with('success', __('laralum_forum::general.comment_added'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laralum\Forum\Models\Comment $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('publicUpdate', $comment);

        $this->validate($request, [
            'comment' => 'required|max:500',
        ]);

        $comment->update([
            'comment' => $request->comment
        ]);

        return redirect()->route('laralum_public::forum.threads.show', ['thread' => $comment->thread->id])
            ->with('success', __('laralum_forum::general.comment_updated', ['id' => $comment->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Laralum\Forum\Models\Comment $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('publicDelete', $comment);

        $comment->delete();
        return redirect()->route('laralum_public::forum.threads.show', ['thread' => $comment->thread->id])
            ->with('success', __('laralum_forum::general.comment_deleted', ['id' => $comment->id]));
    }
}
