<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CourseController extends Controller {

    /**
     * Redirect to a course-specific URL, based on stored state from the database
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function noCourse() {
        /** @var User $user */
        $user = Auth::user();
        if (count($user->courses)) {
            return Redirect::route('index', ['course' => $user->lastAccessedCourse->id]);
        }
        return view('no-courses');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('course-new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CourseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CourseRequest $request) {
        DB::transaction(function () use ($request) {
            Course::create($request->validated())->users()->attach(Auth::user()->getAuthIdentifier());
        });

        return Redirect::route('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        return view('admin.course-edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CourseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CourseRequest $request, Course $course) {
        $course->update($request->validated());
        $request->session()->flash('alert-success', __('Kursdetails erfolgreich gespeichert.'));
        return Redirect::route('admin.course', ['course' => $course->id]);
    }
}
