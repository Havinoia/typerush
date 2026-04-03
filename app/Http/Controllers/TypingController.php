<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Paragraph;
use App\Models\Result;
use Inertia\Inertia;

class TypingController extends Controller
{
    public function index()
    {
        return Inertia::render('Typing/Index', [
            'paragraphs' => Paragraph::all(),
        ]);
    }

    public function getRandomParagraph(Request $request)
    {
        $language = $request->query('language', 'en');
        
        $paragraph = Paragraph::where('language', $language)
            ->inRandomOrder()
            ->first();

        return response()->json($paragraph);
    }

    public function storeResult(Request $request)
    {
        $validated = $request->validate([
            'wpm' => 'required|integer',
            'accuracy' => 'required|numeric',
            'total_chars' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        $result = Result::create(array_merge($validated, [
            'user_id' => $request->user()?->id,
        ]));

        return response()->json([
            'message' => 'Result saved successfully!',
            'result' => $result,
        ]);
    }

    public function leaderboard()
    {
        $topResults = Result::with('user')
            ->orderByDesc('wpm')
            ->orderByDesc('accuracy')
            ->limit(10)
            ->get();

        return Inertia::render('Typing/Leaderboard', [
            'topResults' => $topResults,
        ]);
    }
}
