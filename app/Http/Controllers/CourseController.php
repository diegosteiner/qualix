<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Models\Kurs;
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
        if (count($user->kurse)) {
            return Redirect::route('index', ['kurs' => $user->lastAccessedKurs->id]);
        }
        return view('no-courses');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.newcourse');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CourseStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CourseStoreRequest $request) {
        DB::transaction(function () use ($request) {
            $kurs = Kurs::create($request->validated());

            $kurs->users()->attach(Auth::user()->getAuthIdentifier());
            $kurs->save();
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
        return view('admin.editcourse');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CourseUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CourseUpdateRequest $request, Kurs $kurs) {
        $validatedData = $request->validated();
        $kurs->update($validatedData);

        $request->session()->flash('alert-success', __('Kursdetails erfolgrich gspeicheret'));

        return Redirect::route('admin.kurs', ['kurs' => $kurs->id]);
    }
}
