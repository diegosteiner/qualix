<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantRequest;
use App\Models\Course;
use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.participants.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ParticipantRequest $request
     * @param Course $course
     * @return RedirectResponse
     */
    public function store(ParticipantRequest $request, Course $course)
    {
        Participant::create(array_merge($request->validated(), ['course_id' => $course->id]));

        return Redirect::route('admin.participants', ['course' => $course->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Participant $tn
     * @return Response
     */
    public function edit(Course $course, Participant $tn)
    {
        return view('admin.participants.edit', ['tn' => $tn]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ParticipantRequest $request
     * @param Course $course
     * @param Participant $tn
     * @return RedirectResponse
     */
    public function update(ParticipantRequest $request, Course $course, Participant $tn)
    {
        if ($request->file('image') && $tn->image_url) {
            Storage::delete($tn->image_url);
        }

        $tn->update($request->validated());

        $request->session()->flash('alert-success', __('TN erfolgreich gespeichert.'));
        return Redirect::route('admin.participants', ['course' => $course->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Course $course
     * @param Participant $tn
     * @return RedirectResponse
     */
    public function destroy(Request $request, Course $course, Participant $tn)
    {
        if ($tn->image_url) {
            Storage::delete($tn->image_url);
        }
        $tn->delete();
        $request->session()->flash('alert-success', __('TN erfolgreich gelöscht.'));
        return Redirect::route('admin.participants', ['course' => $course->id]);
    }
}
