<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use App\Models\Score;

class QuizController extends Controller
{
    protected $quizJson;

    public function __construct()
    {
        $this->quizJson = File::json(base_path('resources\json\quiz.json'));
    }
    
    public function show(){
        return Inertia::render('Quiz', [
            'quizName' => $this->quizJson['quiz_name'],
            'questions' => array_values($this->quizJson['questions'])
        ]);
    }

    public function storeScore(Request $request)
    {
        $score = new Score;
        $score->name = $request->name;
        $score->score = $request->score;
        $score->save();
    }

    public function scores()
    {
        $scores = Score::all();
        return $scores;
    }
}
