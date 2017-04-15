<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Laralum\Forum\Models\Category;

class PublicCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return view('laralum_forum::public.categories.index', ['categories' => $categories]);
    }

    /**
     * Display the specified resource.
     *
     * @param \Laralum\Forum\Models\Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('laralum_forum::public.categories.show', ['category' => $category]);
    }
}
