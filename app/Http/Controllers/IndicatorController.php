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
        $data = $request->validated();
        DB::transaction(function () use ($request, $course, $data) {
            $data = array_merge($data, ['course_id' => $course->id, 'requirement_id' => $data['requirements']]);
            $indicator = Indicator::create($data);
            $request->session()->flash('alert-success', __('t.views.admin.indicators.create_success'));
        });
        return Redirect::route('admin.requirements.edit', ['course' => $course->id, 'requirement' => $data['requirements']]);
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
    public function update(IndicatorRequest $request, Course $course, Indicator $indicator) {
        DB::transaction(function () use ($request, $course, $indicator) {
            $data = $request->validated();
            $indicator->update($data);
            $request->session()->flash('alert-success', __('t.views.admin.indicators.edit_success'));
        });
        return Redirect::route('admin.requirements.edit', ['course' => $course->id, 'requirement' => $indicator->requirement_id]);
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
        return Redirect::route('admin.requirements.edit', ['course' => $course->id, 'requirement' => $indicator->requirement_id]);
    }
}
