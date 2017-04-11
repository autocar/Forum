<?php

namespace Laralum\Forum\Controllers;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laralum\Forum\Models\Settings;
use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Thread;
use Illuminate\Http\Request;

class PublicThreadController extends Controller
{
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $this->authorize('publicCreate', Thread::class);
        return view('laralum_forum::public.threads.create', ['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('publicCreate', Thread::class);
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
        return redirect()->route('laralum_public::forum.categories.show', ['category' => $request->category])
            ->with('success', __('laralum_forum::general.category_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Thread $thread)
    {
        return view('laralum_forum::public.threads.show', ['thread' => $thread]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Laralum\Forum\Models\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        $this->authorize('publicUpdate', $thread);
        return view('laralum_forum::public.threads.edit', ['thread' => $thread, 'categories' => Category::all()]);
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
            'content' => 'required|max:2000',
            'description' => 'required|max:255',
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

        return redirect()->route('laralum_public::forum.threads.show', ['thread' => $thread])
            ->with('success', __('laralum_forum::general.thread_updated', ['id' => $thread->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('publicDelete', $thread);
        $thread->deleteComments();
        $thread->delete();
        return redirect()->route('laralum_public::forum.categories.index')
            ->with('success', __('laralum_forum::general.thread_deleted',['id' => $thread->id]));
    }

}
