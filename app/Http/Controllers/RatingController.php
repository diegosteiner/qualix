<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Course;
use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RatingController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('admin.ratings.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RatingRequest $request
     * @param Course $course
     * @return RedirectResponse
     */
    public function store(RatingRequest $request, Course $course) {
        DB::transaction(function () use ($request, $course) {
            $data = $request->validated();
            $rating = Rating::create(array_merge($data, ['course_id' => $course->id]));

            $request->session()->flash('alert-success', __('t.views.admin.ratings.create_success'));
        });
        return Redirect::route('admin.ratings', ['course' => $course->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @param Rating $rating
     * @return Response
     */
    public function edit(Course $course, Rating $rating) {
        return view('admin.ratings.edit', ['rating' => $rating]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RatingRequest $request
     * @param Course $course
     * @param Rating $rating
     * @return RedirectResponse
     */
    public function update(RatingRequest $request, Course $course, Rating $rating) {
        DB::transaction(function () use ($request, $course, $rating) {
            $data = $request->validated();
            $rating->update($data);

            $request->session()->flash('alert-success', __('t.views.admin.ratings.edit_success'));
        });
        return Redirect::route('admin.ratings', ['course' => $course->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Course $course
     * @param Rating $rating
     * @return RedirectResponse
     */
    public function destroy(Request $request, Course $course, Rating $rating) {
        $rating->delete();
        $request->session()->flash('alert-success', __('t.views.admin.ratings.delete_success'));
        return Redirect::route('admin.ratings', ['course' => $course->id]);
    }
}
