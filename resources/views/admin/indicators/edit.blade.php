@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.indicators.edit')}}</template>

        @component('components.form', ['route' => ['admin.indicators.update', ['course' => $course->id, 'indicator' => $indicator->id]]])

            <input-text @forminput('content', $indicator->content) label="{{__('t.models.indicator.content')}}" required autofocus></input-text>

            <input-multi-select
                @forminput('requirements', $indicator->requirements->pluck('id')->join(','))
                label="{{__('t.models.indicator.requirements')}}"
                :options="{{ json_encode($course->requirements->map->only('id', 'content')) }}"
                display-field="content"
                multiple></input-multi-select>

            <button-submit></button-submit>

        @endcomponent

    </b-card>



@endsection
