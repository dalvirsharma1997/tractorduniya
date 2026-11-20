<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $posts = News::where('status', 'published')->latest('published_at')->paginate(9);

        return view('news.index', compact('posts'));
    }

    public function show(News $news): View
    {
        abort_unless($news->status === 'published', 404);

        $related = News::where('status', 'published')
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('news.show', ['post' => $news, 'related' => $related]);
    }
}
