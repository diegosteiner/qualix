@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.ratingscales.edit')}}</template>
        @component('components.form', ['route' => ['admin.ratingscales.update', ['course' => $course->id, 'ratingscale' => $ratingscale->id]]])
            <input-text @forminput('name', $ratingscale->name) label="{{__('t.models.ratingscale.name')}}" required autofocus></input-text>
            <button-submit></button-submit>
        @endcomponent
    </b-card>

    @include('admin.ratings.index', ['ratingscale' => $ratingscale])
@endsection
