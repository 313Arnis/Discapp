<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('courseHoles')->orderBy('name')->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'holes' => 'required|integer|min:1|max:36',
            'rating_1000_score' => 'required|numeric|gt:0',
            'rating_per_throw' => 'required|numeric|gt:0',
        ]);

        DB::transaction(function () use ($validated) {
            $course = Course::create($validated);

            for ($i = 1; $i <= $validated['holes']; $i++) {
                $course->courseHoles()->create([
                    'hole_number' => $i,
                    'par' => 3,
                ]);
            }
        });

        return redirect()->route('courses.index')
            ->with('success', 'Trase veiksmīgi izveidota!');
    }

    public function edit(Course $course)
    {
        $course->load('courseHoles');
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'par' => 'required|array',
            'par.*' => 'required|integer|min:2|max:6',
            'rating_1000_score' => 'sometimes|required|numeric|gt:0',
            'rating_per_throw' => 'sometimes|required|numeric|gt:0',
        ]);

        DB::transaction(function () use ($course, $validated) {
            $existingNumbers = $course->courseHoles()->pluck('hole_number')->map(fn ($n) => (int) $n)->all();
            $submittedNumbers = array_map('intval', array_keys($validated['par']));
            sort($existingNumbers);
            sort($submittedNumbers);

            if ($existingNumbers !== $submittedNumbers) {
                throw ValidationException::withMessages([
                    'par' => 'Jānorāda PAR vērtības visiem trases groziem.',
                ]);
            }

            foreach ($validated['par'] as $holeNumber => $par) {
                $course->courseHoles()->where('hole_number', $holeNumber)->update(['par' => $par]);
            }

            $params = array_intersect_key($validated, array_flip(['rating_1000_score', 'rating_per_throw']));
            if ($params !== []) {
                $course->update($params);
            }
        });

        return redirect()->route('courses.edit', $course)
            ->with('success', 'Trases PAR un reitinga parametri veiksmīgi saglabāti!');
    }

    public function destroy(Course $course)
    {
        DB::transaction(function () use ($course) {
            $course->courseHoles()->delete();
            $course->delete();
        });

        return redirect()->route('courses.index')
            ->with('success', 'Trase veiksmīgi izdzēsta!');
    }
}
