<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Article::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->filter_status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $articles = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($articles);
        }

        return view('articles.index', [
            'articles' => $articles,
            'search' => $request->search,
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $article = \App\Models\Article::create($validated);

        if ($request->wantsJson()) {
            return response()->json($article, 201);
        }

        return redirect()->route('articles.index');
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $article->update($validated);

        if ($request->wantsJson()) {
            return response()->json($article);
        }

        return redirect()->route('articles.index');
    }

    public function destroy(\App\Models\Article $article, \Illuminate\Http\Request $request)
    {
        $article->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('articles.index');
    }
}