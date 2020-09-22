@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.ratings.edit', ['name' => $rating->ratingscale->name])}}</template>
        @component('components.form', ['route' => ['admin.ratings.update', ['course' => $course->id, 'rating' => $rating->id]]])
            <input-text @forminput('name', $rating->name) label="{{__('t.models.rating.name')}}" required autofocus></input-text>
            <input-hidden @forminput('ratingscales', $rating->ratingscale->id) label="{{__('t.models.rating.ratingscale')}}" required></input-hidden>
            <button-submit></button-submit>
        @endcomponent
    </b-card>

@endsection
