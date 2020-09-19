@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.indicators.new')}}</template>

        @component('components.form', ['route' => ['admin.indicators.store', ['course' => $course->id]]])


            <input-text @forminput('content') label="{{__('t.models.indicator.content')}}" required autofocus></input-text>

            <input-multi-select
                @forminput('requirements')
                label="{{__('t.models.indicator.requirements')}}"
                :options="{{ json_encode($course->requirements->map->only('id', 'content')) }}"
                display-field="content"
                multiple></input-multi-select>

            {{-- <input-checkbox @forminput('mandatory', false) label="{{__('t.models.requirement.mandatory')}}"></input-checkbox> --}}


            <button-submit label="{{__('t.global.add')}}">

                @component('components.help-text', ['id' => 'indicatorsHelp', 'key' => 't.views.admin.indicators.what_are_indicators'])@endcomponent

            </button-submit>

        @endcomponent

    </b-card>


    <b-card>
        <template #header>{{__('t.views.admin.indicators.existing', ['requirementName' => $course->name])}}</template>

        @if (count($course->indicators))

            @php
                $fields = [
                    __('t.models.indicator.content') => function(\App\Models\Indicator $indicator) { return $indicator->content; },
                    //__('t.models.indicator.requirements') => function(\App\Models\Requirement $requirement) { return $indicator->requirements; },
                    //__('t.models.requirement.num_indicators') => function(\App\Models\Requirement $requirement) { return count($requirement->indicators); },
                    // __('t.models.indicator.num_observations') => function(\App\Models\Requirement $requirement) { return count($requirement->observations); },
                ];
                if ($course->archived) {
                    unset($fields[__('t.models.indicator.num_observations')]);
                    // unset($fields[__('t.models.requirement.num_indicators')]);
                }
            @endphp
            @component('components.responsive-table', [
                'data' => $course->indicators,
                'fields' => $fields,
                'actions' => [
                    'edit' => function(\App\Models\Indicator $indicator) use ($course) { return route('admin.indicators.edit', ['course' => $course->id, 'indicator' => $indicator->id]); },
                    'delete' => function(\App\Models\Indicator $indicator) use ($course) { return [
                        'text' => __('t.views.admin.indicators.really_delete') . ($course->archived ? '' : ' ' . trans_choice('t.views.admin.indicators.observations_on_indicator', $indicator->observations)),
                        'route' => ['admin.indicators.delete', ['course' => $course->id, 'indicator' => $indicator->id]],
                    ];},
                  ]
            ])@endcomponent

        @else

            {{__('t.views.admin.indicators.no_indicators')}}

            @component('components.help-text', ['id' => 'noIndicatorsHelp', 'key' => 't.views.admin.indicators.are_indicators_required'])@endcomponent

        @endif

    </b-card>

@endsection
