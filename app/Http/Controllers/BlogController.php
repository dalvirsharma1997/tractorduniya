<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Blog::where('status', 'published')->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(Blog $blog): View
    {
        abort_unless($blog->status === 'published', 404);

        $related = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', ['post' => $blog, 'related' => $related]);
    }
}
