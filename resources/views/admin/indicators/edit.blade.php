@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.indicators.edit', ['name' => $indicator->requirement->content])}}</template>
        @component('components.form', ['route' => ['admin.indicators.update', ['course' => $course->id, 'indicator' => $indicator->id]]])
            <input-text @forminput('content', $indicator->content) label="{{__('t.models.indicator.content')}}" required autofocus></input-text>
            <input-hidden @forminput('requirements', $indicator->requirement->id) label="{{__('t.models.indicator.requirement')}}" required></input-hidden>
            <button-submit></button-submit>
        @endcomponent
    </b-card>



@endsection
