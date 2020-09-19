<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingscaleRequest;
use App\Models\Course;
use App\Models\Ratingscale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RatingscaleController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('admin.ratingscales.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RatingscaleRequest $request
     * @param Course $course
     * @return RedirectResponse
     */
    public function store(RatingscaleRequest $request, Course $course) {
        DB::transaction(function () use ($request, $course) {
            $data = $request->validated();
            $ratingscale = Ratingscale::create(array_merge($data, ['course_id' => $course->id]));

            $request->session()->flash('alert-success', __('t.views.admin.ratingscales.create_success'));
        });
        return Redirect::route('admin.v', ['course' => $course->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @param Requirement $requirement
     * @return Response
     */
    public function edit(Course $course, Ratingscale $ratingscale) {
        return view('admin.ratingscales.edit', ['ratingscale' => $ratingscale]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RatingscaleRequest $request
     * @param Course $course
     * @param Ratingscale $ratingscale
     * @return RedirectResponse
     */
    public function update(RatingscaleRequest $request, Course $course, Ratingscale $ratingscale) {
        DB::transaction(function () use ($request, $course, $ratingscale) {
            $data = $request->validated();
            $ratingscale->update($data);

            $request->session()->flash('alert-success', __('t.views.admin.ratingscales.edit_success'));
        });
        return Redirect::route('admin.ratingscales', ['course' => $course->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Course $course
     * @param Ratingscale $ratingscale
     * @return RedirectResponse
     */
    public function destroy(Request $request, Course $course, Ratingscale $ratingscale) {
        $requirement->delete();
        $request->session()->flash('alert-success', __('t.views.admin.ratingscales.delete_success'));
        return Redirect::route('admin.ratingscales', ['course' => $course->id]);
    }
}
