<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Thread;
use Laralum\Forum\Models\Comment;
use Laralum\Forum\Models\Settings;
use Illuminate\Support\Facades\Auth;
use GrahamCampbell\Markdown\Facades\Markdown;

class ThreadController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Thread::class);
        return view('laralum_forum::laralum.threads.create', ['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Thread::class);
        $this->validate($request, [
            'title' => 'required|max:255',
            'category' => 'required|exists:laralum_forum_categories,id',
            'description' => 'required|max:255',
            'content' => 'required|max:2000',
        ]);

        if (Settings::first()->text_editor == "markdown") {
            $msg = Markdown::convertToHtml($request->content);
        } elseif (Settings::first()->text_editor == "wysiwyg") {
            $msg = $request->content;
        } else {
            $msg = htmlentities($request->content);
        }

        Thread::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $msg,
            'user_id' => Auth::id(),
            'category_id' => $request->category,
        ]);
        return redirect()->route('laralum::forum.categories.show', ['category' => $request->category])
            ->with('success', __('laralum_forum::general.category_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Laralum\Forum\Models\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread)
    {
        $this->authorize('view', $thread);
        return view('laralum_forum::.laralum.threads.show', ['thread' => $thread]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Laralum\Forum\Models\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        $this->authorize('update', $thread);
        return view('laralum_forum::.laralum.threads.edit', ['thread' => $thread, 'categories' => Category::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laralum\Forum\Models\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);
        $this->validate($request, [
            'title' => 'required|max:255',
            'category' => 'required|exists:laralum_forum_categories,id',
            'description' => 'required|max:255',
            'content' => 'required|max:2000',
        ]);

        if (Settings::first()->text_editor == "markdown") {
            $msg = Markdown::convertToHtml($request->content);
        } elseif (Settings::first()->text_editor == "wysiwyg") {
            $msg = $request->content;
        } else {
            $msg = htmlentities($request->content);
        }

        $thread->update([
            'title' => $request->title,
            'category_id' => $request->category,
            'description' => $request->description,
            'content' => $msg,
        ]);

        return redirect()->route('laralum::forum.threads.show', ['thread' => $thread])
            ->with('success', __('laralum_forum::general.thread_updated', ['id' => $thread->id]));
    }

    /**
     * confirm destroy of the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmDestroy(Request $request, Thread $thread)
    {
        $this->authorize('delete', $thread);
        return view('laralum::pages.confirmation', [
            'method' => 'DELETE',
            'message' => __('laralum_forum::general.sure_del_thread', ['thread' => $thread->title]),
            'action' => route('laralum::forum.threads.destroy', ['thread' => $thread->id]),
        ]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->deleteComments();
        $thread->delete();
        return redirect()->route('laralum::forum.categories.index')
            ->with('success', __('laralum_forum::general.thread_deleted',['id' => $thread->id]));
    }
}
