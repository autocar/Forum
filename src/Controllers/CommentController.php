<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Thread;
use Laralum\Forum\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category, Thread $thread)
    {
        $this->authorize('create', Comment::class);

        $this->validate($request, [
            'comment' => 'required|min:5|max:500',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'thread_id' => $thread->id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('laralum::forum.categories.threads.show', ['category' => $category->id, 'thread' => $thread->id])->with('success', __('laralum_forum::general.comment_sent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, Thread $thread, Comment $comment)
    {
        $this->authorize('update', $comment);

        $this->validate($request, [
            'comment' => 'required|min:5|max:500',
        ]);

        $comment->update([
            'comment' => $request->comment
        ]);

        return redirect()->route('laralum::forum.categories.threads.show', ['category' => $category->id, 'thread' => $thread->id])->with('success', __('laralum_forum::general.comment_updated', ['id' => $comment->id]));

    }

    /**
     * confirm destroy of the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmDestroy(Request $request, Category $category, Thread $thread, Comment $comment)
    {
        $this->authorize('delete', $comment);

        return view('laralum::pages.confirmation', [
            'method' => 'DELETE',
            'message' => __('laralum_forum::general.sure_del_comment', ['comment' => $comment->comment]),
            'action' => route('laralum::forum.categories.threads.comments.destroy', ['category' => $category->id, 'thread' => $thread->id, 'comment' => $comment->id]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Thread $thread, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();
        return redirect()->route('laralum::forum.categories.threads.show', ['category' => $category->id, 'thread' => $thread->id])->with('success', __('laralum_forum::general.comment_deleted', ['id' => $comment->id]));
    }
}
