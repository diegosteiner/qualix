<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndicatorRequest;
use App\Models\Course;
use App\Models\Requirement;
use App\Models\Indicator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IndicatorController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('admin.indicators.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IndicatorRequest $request
     * @param Course $course
     * @return RedirectResponse
     */
    public function store(IndicatorRequest $request, Course $course) {
        DB::transaction(function () use ($request, $course) {
            $data = $request->validated();
            $indicator = Indicator::create(array_merge($data, ['course_id' => $course->id]));
            $indicator->requirements()->attach(array_filter(explode(',', $data['requirements'])));

            $request->session()->flash('alert-success', __('t.views.admin.indicators.create_success'));
        });
        return Redirect::route('admin.indicators', ['course' => $course->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @param Indicator $indicator
     * @return Response
     */
    public function edit(Course $course, Indicator $indicator) {
        return view('admin.indicators.edit', ['indicator' => $indicator]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param IndicatorRequest $request
     * @param Indicator $indicator
     * @return RedirectResponse
     */
    public function update(RequirementRequest $request, Course $course, Indicator $indicator) {
        DB::transaction(function () use ($request, $indicator) {
            $data = $request->validated();
            $indicator->update($data);
            $request->session()->flash('alert-success', __('t.views.admin.indicators.edit_success'));
        });
        return Redirect::route('admin.indicators', ['course' => $course->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Requirement $requirement
     * @param Indicator $indicator
     * @return RedirectResponse
     */
    public function destroy(Request $request, Course $course, Indicator $indicator) {
        $indicator->delete();
        $request->session()->flash('alert-success', __('t.views.admin.indicators.delete_success'));
        return Redirect::route('admin.indicators', ['course' => $course->id]);
    }
}
