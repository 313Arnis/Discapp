<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Visas trases
     */
    public function index()
    {
        $courses = Course::with('courseHoles')
            ->orderBy('name')
            ->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Jaunas trases forma
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Izveidot jaunu trasi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'holes' => 'required|integer|min:1|max:36',
            'rating_1000_score' => 'required|numeric|min:0',
            'rating_per_throw' => 'required|numeric|min:0',
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

        return redirect()
            ->route('courses.index')
            ->with('success', 'Trase veiksmīgi izveidota!');
    }

    /**
     * Rediģēt trasi
     */
    public function edit(Course $course)
    {
        $course->load('courseHoles');

        return view('courses.edit', compact('course'));
    }

    /**
     * Saglabāt trases izmaiņas
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'par' => 'required|array',
            'par.*' => 'required|integer|min:2|max:6',
        ]);

        foreach ($validated['par'] as $holeNumber => $par) {

            $course->courseHoles()
                ->where('hole_number', $holeNumber)
                ->update([
                    'par' => $par,
                ]);
        }

        return redirect()
            ->route('courses.edit', $course)
            ->with('success', 'Trases PAR vērtības veiksmīgi saglabātas!');
    }

    /**
     * Dzēst trasi
     */
    public function destroy(Course $course)
    {
        $course->courseHoles()->delete();

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Trase veiksmīgi izdzēsta!');
    }
}