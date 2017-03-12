<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Thread;
use Laralum\Forum\Models\Comment;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        $this->authorize('create', Thread::class);
        return view('laralum_forum::threads.create', ['category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category)
    {
        $this->authorize('create', Thread::class);
        $this->validate($request, [
            'title' => 'required|min:5|max:60',
            'content' => 'required|max:1500',
        ]);
        Thread::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'category_id' => $category->id,
        ]);
        return redirect()->route('laralum::forum.categories.show', ['category' => $category->id])->with('success', __('laralum_forum::category_created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Thread $thread)
    {
        $this->authorize('view', $thread);
        return view('laralum_forum::threads.show', ['thread' => $thread]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Thread $thread)
    {
        $this->authorize('update', $thread);
        return view('laralum_forum::threads.edit', ['thread' => $thread]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, Thread $thread)
    {
        $this->authorize('update', $thread);
        $this->validate($request, [
            'title' => 'required|min:5|max:60',
            'content' => 'required|max:1500',
        ]);

        $thread->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('laralum::forum.categories.threads.show', ['category' => $category, 'thread' => $thread])->with('success', __('laralum_forum::general.thread_updated', ['id' => $thread->id]));
    }

    /**
     * confirm destroy of the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmDestroy(Request $request, Category $category, Thread $thread)
    {
        $this->authorize('delete', $thread);
        return view('laralum::pages.confirmation', [
            'method' => 'DELETE',
            'message' => __('laralum_forum::general.sure_del_thread', ['thread' => $thread->title]),
            'action' => route('laralum::forum.categories.threads.destroy', ['category' => $category->id, 'thread' => $thread->id]),
        ]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->deleteComments();
        $thread->delete();
        return redirect()->route('laralum::forum.categories.show', ['category' => $category->id])->with('success', __('laralum_forum::general.thread_deleted',['id' => $thread->id]));
    }
}
