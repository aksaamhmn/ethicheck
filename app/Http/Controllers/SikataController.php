<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\CaseStudy;
use Illuminate\Support\Facades\Validator;

class SikataController extends Controller
{
    /**
     * List available topics (Stage 0)
     */
    public function topics()
    {
        // Enforce custom ordering: PS (pelecehan-seksual), S (sara), E (ekonomi)
        $topics = Topic::select('id', 'slug', 'name')
            ->orderByRaw('FIELD(slug, "pelecehan-seksual","sara","ekonomi")')
            ->get();
        return response()->json(['topics' => $topics]);
    }

    /**
     * Start a game: choose a case under a topic (Stage 1 init)
     * Request: { topic_slug }
     */
    public function start(Request $request)
    {
        $data = $request->validate([
            'topic_slug' => 'required|exists:topics,slug',
        ]);

        $topic = Topic::where('slug', $data['topic_slug'])->first();
        // Prefer the most recently seeded active case to align with Stage 2 dataset
        $case = CaseStudy::where('topic_id', $topic->id)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->with(['sentences' => function ($q) {
                $q->orderBy('sentence_index');
            }])
            ->first();

        if (!$case) {
            return response()->json(['error' => 'No active case for topic'], 404);
        }

        // Return case info and sentences (stateless start)
        return response()->json([
            'case' => [
                'id' => $case->id,
                'title' => $case->title,
                // Build full article by numbering each sentence like (1) ... (2) ...
                'article' => $case->sentences->map(fn($s) => '(' . $s->sentence_index . ') ' . $s->text)->implode(' '),
                'sentences' => $case->sentences->map(fn($s) => [
                    'index' => $s->sentence_index,
                    'text' => $s->text,
                ])->values(),
            ],
        ]);
    }

    /**
     * Stage 2: list correction options per sentence for a case
     * Request: { case_id }
     */
    public function corrections(Request $request)
    {
        $data = $request->validate([
            'case_id' => 'required|integer|exists:cases,id',
        ]);
        $case = CaseStudy::with(['sentences.corrections'])->find($data['case_id']);
        if (!$case) {
            return response()->json(['error' => 'Case not found'], 404);
        }
        $payload = [];
        foreach ($case->sentences as $s) {
            if ($s->corrections && $s->corrections->count() > 0) {
                $payload[] = [
                    'index' => $s->sentence_index,
                    'original' => $s->text,
                    'options' => $s->corrections->map(fn($c) => [
                        'id' => $c->id,
                        'text' => $c->text,
                    ])->values(),
                ];
            }
        }
        return response()->json(['corrections' => $payload]);
    }

    /**
     * Stage 2: submit chosen corrections
     * Request: { case_id, answers: [{index, correction_id}] }
     */
    public function correct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'case_id' => 'required|integer|exists:cases,id',
            'answers' => 'required|array|min:1',
            'answers.*.index' => 'required|integer|min:1',
            'answers.*.correction_id' => 'required|integer|exists:sentence_corrections,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input', 'messages' => $validator->errors()], 422);
        }

        $case = CaseStudy::with(['sentences.corrections'])->find($request->input('case_id'));
        if (!$case) {
            return response()->json(['error' => 'Case not found'], 404);
        }

        $answers = collect($request->input('answers'));
        $byIndex = [];
        foreach ($case->sentences as $s) {
            $byIndex[$s->sentence_index] = $s;
        }

        $result = [];
        $correctCount = 0;
        $totalWithOptions = 0;
        foreach ($answers as $ans) {
            $idx = (int)$ans['index'];
            if (!isset($byIndex[$idx])) continue;
            $sentence = $byIndex[$idx];
            if (!$sentence->corrections || $sentence->corrections->count() === 0) continue;
            $totalWithOptions++;
            $chosen = $sentence->corrections->firstWhere('id', (int)$ans['correction_id']);
            $correctOpt = $sentence->corrections->firstWhere('is_correct', true);
            if ($chosen) {
                $isCorrect = (bool)$chosen->is_correct;
                if ($isCorrect) $correctCount++;
                $result[] = [
                    'index' => $idx,
                    'chosen' => ['id' => $chosen->id, 'text' => $chosen->text, 'is_correct' => $isCorrect],
                    'rationale' => $chosen->rationale,
                    'correct' => $correctOpt ? ['id' => $correctOpt->id, 'text' => $correctOpt->text, 'rationale' => $correctOpt->rationale] : null,
                ];
            }
        }

        // scoring: max 40 points distributed over sentences with correction options
        $score = 0;
        if ($totalWithOptions > 0) {
            $per = 40 / $totalWithOptions;
            $score = (int) round($correctCount * $per);
        }

        return response()->json([
            'case_id' => $case->id,
            'results' => $result,
            'score_correct' => $score,
            'final_title' => $case->final_title,
            'final_article' => $case->final_article,
            'next_stage' => 'summary',
        ]);
    }

    /**
     * Submit selected sentence indexes for violations (Stage 1 evaluation)
     * Request: { case_id, selected: [indexes...] }
     */
    public function identify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'case_id' => 'required|integer|exists:cases,id',
            'selected' => 'required|array|min:1',
            'selected.*' => 'integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input', 'messages' => $validator->errors()], 422);
        }

        $case = CaseStudy::with(['sentences.violations'])->find($request->input('case_id'));
        if (!$case) {
            return response()->json(['error' => 'Case not found'], 404);
        }

        $selected = collect($request->input('selected'))->unique()->values();

        // Build sets of actual violation sentence indexes and map of violations by sentence index
        $violationSentences = collect();
        $violationsByIndex = [];
        foreach ($case->sentences as $sentence) {
            if ($sentence->violations->count() > 0) {
                $violationSentences->push($sentence->sentence_index);
                $violationsByIndex[$sentence->sentence_index] = $sentence->violations->map(function ($v) use ($sentence) {
                    return [
                        'index' => $sentence->sentence_index,
                        'violation_title' => $v->violation_title,
                        'snippet' => $v->snippet,
                        'description' => $v->description,
                        'legal_basis' => $v->legal_basis,
                        'severity' => $v->severity,
                    ];
                })->values();
            }
        }

        $correct = $selected->filter(fn($i) => $violationSentences->contains($i))->values();
        $falsePositives = $selected->filter(fn($i) => !$violationSentences->contains($i))->values();

        // Score simple formula: max 60 for identification
        $scoreIdentify = $this->computeIdentifyScore(
            $correct->count(),
            $falsePositives->count(),
            $violationSentences->count()
        );

        // Explanations for ALL violation sentences (not only those user selected)
        $explanations = [];
        foreach ($case->sentences as $sentence) {
            if ($sentence->violations->count() > 0) {
                foreach ($sentence->violations as $v) {
                    $explanations[] = [
                        'index' => $sentence->sentence_index,
                        'violation_title' => $v->violation_title,
                        'snippet' => $v->snippet,
                        'description' => $v->description,
                        'legal_basis' => $v->legal_basis,
                        'severity' => $v->severity,
                    ];
                }
            }
        }

        return response()->json([
            'case_id' => $case->id,
            'correct_indexes' => $correct,
            'false_positive_indexes' => $falsePositives,
            'all_violation_indexes' => $violationSentences,
            'violations_by_index' => $violationsByIndex,
            'explanations' => $explanations,
            'score_identify' => $scoreIdentify,
            'next_stage' => 'correct',
        ]);
    }

    private function computeIdentifyScore(int $correctCount, int $falsePositives, int $totalViolations): int
    {
        if ($totalViolations === 0) {
            return 0;
        }
        $basePer = 60 / max(1, $totalViolations); // value per actual violation
        $score = $correctCount * $basePer;
        $penalty = $falsePositives * ($basePer / 2); // half penalty
        $final = (int) round(max(0, min(60, $score - $penalty)));
        return $final;
    }
}
