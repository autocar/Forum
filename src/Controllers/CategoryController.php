<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Thread;
use Laralum\Forum\Models\Comment;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('laralum_forum::categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('laralum_forum::categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5|max:50',
            'description' => 'required|max:100',
        ]);
        Category::create($request->all());
        return redirect()->route('laralum::forum.categories.index')->with('success', __('laralum_forum::general.category_created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Laralum\Forum\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('laralum_forum::categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Laralum\Forum\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('laralum_forum::categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laralum\Forum\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'title' => 'required|min:5|max:50',
            'description' => 'required|max:100',
        ]);
        $category->update($request->all());
        return redirect()->route('laralum::forum.categories.index')->with('success', __('laralum_forum::general.category_updated',['id' => $category->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Laralum\Forum\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function confirmDestroy(Category $category)
    {

        return view('laralum::pages.confirmation', [
            'method' => 'DELETE',
            'message' => __('laralum_forum::general.sure_del_category', ['category' => $category->title]),
            'action' => route('laralum::forum.categories.destroy', ['category' => $category->id]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Laralum\Forum\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->deleteComments();
        $category->deleteThreads();
        $category->delete();

        return redirect()->route('laralum::forum.categories.index');
    }
}
