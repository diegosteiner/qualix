<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObservationCreateRequest;
use App\Http\Requests\ObservationUpdateRequest;
use App\Models\Observation;
use App\Models\Block;
use App\Models\Course;
use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ObservationController extends Controller {
    /**
     * Display a form to create a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        return view('observation.new', ['participant_id' => $request->input('tn'), 'block_id' => $request->input('block')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ObservationCreateRequest $request
     * @param Course $course
     * @return RedirectResponse
     */
    public function store(ObservationCreateRequest $request, Course $course) {
        $data = $request->validated();
        DB::transaction(function() use ($request, $course, $data) {
            $participant_ids = explode(',', $data['participant_ids']);
            $ma_ids = array_filter(explode(',', $data['requirement_ids']));
            $qk_ids = array_filter(explode(',', $data['qk_ids']));

            foreach ($participant_ids as $participant_id) {
                $observation = Observation::create(array_merge($data, ['participant_id' => $participant_id, 'course_id' => $course->id, 'user_id' => Auth::user()->getAuthIdentifier()]));
                $observation->requirements()->attach($ma_ids);
                $observation->categories()->attach($qk_ids);
            }

            if (count($participant_ids) > 1) {
                $request->session()->flash('alert-success', __('Beobachtungen erfasst. Mässi!'));
            } else {
                $tn = Participant::find($participant_ids[0]);
                $request->session()->flash('alert-success', __('Beobachtung erfasst. Mässi!') . ' <a href="' . route('tn.detail', ['course' => $course->id, 'tn' => $tn->id]) . '">' . __('Zurück zu :name', ['name' => $tn->scout_name]) . ' <i class="fas fa-arrow-right"></i></a>');
            }
        });

        return Redirect::route('observation.new', ['course' => $course->id, 'tn' => $data['participant_ids'], 'block' => $data['block_id']]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @param Observation $observation
     * @return Response
     */
    public function edit(Course $course, Observation $observation) {
        return view('observation.edit', ['observation' => $observation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ObservationUpdateRequest $request
     * @param Course $course
     * @param Observation $observation
     * @return RedirectResponse
     */
    public function update(ObservationUpdateRequest $request, Course $course, Observation $observation) {
        DB::transaction(function () use ($request, $observation) {
            $data = $request->validated();
            $observation->update($data);

            $observation->requirements()->detach();
            $observation->requirements()->attach(array_filter(explode(',', $data['requirement_ids'])));

            $observation->categories()->detach();
            $observation->categories()->attach(array_filter(explode(',', $data['qk_ids'])));
        });

        $request->session()->flash('alert-success', __('Beobachtung aktualisiert.'));

        return Redirect::route('tn.detail', ['course' => $course->id, 'tn' => $observation->participant->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Course $course
     * @param Block $block
     * @return RedirectResponse
     */
    public function destroy(Request $request, Course $course, Observation $observation) {
        $observation->delete();
        $request->session()->flash('alert-success', __('Beobachtung gelöscht.'));
        return Redirect::route('tn.detail', ['course' => $course->id, 'tn' => $observation->participant->id]);
    }

    /**
     * Show an overview table with info about which user has made how many observations about which TN.
     *
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function overview(Request $request, Course $course) {
        return view('overview', ['tns' => $course->participants->all()]);
    }
}
