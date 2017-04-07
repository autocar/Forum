<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Thread;

class PublicThreadController extends Controller
{
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
}
