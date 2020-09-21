@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.ratings.edit')}}</template>

        @component('components.form', ['route' => ['admin.ratings.update', ['course' => $course->id, 'rating' => $rating->id]]])

            <input-text @forminput('name', $rating->name) label="{{__('t.models.rating.name')}}" required autofocus></input-text>

            <input-multi-select
                @forminput('ratingscales', $rating->ratingscale->id)
                label="{{__('t.models.rating.ratingscale')}}"
                :options="{{ json_encode($course->ratingscales->map->only('id', 'name')) }}"
                display-field="name"
                ></input-multi-select>
            <button-submit></button-submit>

        @endcomponent

    </b-card>

@endsection
