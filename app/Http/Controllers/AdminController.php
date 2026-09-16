<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function courses()
    {
        $courses = Course::orderBy('name')->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function createCourse()
    {
        return view('admin.courses.create');
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Course::create($validated);

        return redirect()
            ->route('admin.courses')
            ->with('success', 'Trase veiksmīgi pievienota!');
    }

    public function editCourse(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);

        return redirect()
            ->route('admin.courses')
            ->with('success', 'Trase veiksmīgi atjaunināta!');
    }

    public function deleteCourse(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('admin.courses')
            ->with('success', 'Trase veiksmīgi izdzēsta!');
    }
}