@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.ratingscales.edit')}}</template>

        @component('components.form', ['route' => ['admin.ratingscales.update', ['course' => $course->id, 'ratingscale' => $ratingscale->id]]])

            <input-text @forminput('name', $ratingscale->name) label="{{__('t.models.ratingscale.name')}}" required autofocus></input-text>

            <button-submit></button-submit>

        @endcomponent

    </b-card>


    <b-card>
        <template #header>{{__('t.views.admin.ratings.new')}}</template>

            @component('components.form', ['route' => ['admin.ratings.store', ['course' => $course->id]]])
            <input-text @forminput('name') label="{{__('t.models.rating.name')}}" required autofocus></input-text>

            <input-multi-select
                @forminput('ratingscales', $course->ratingscales->pluck('id')->join(','))
                label="{{__('t.models.rating.ratingscale')}}"
                :options="{{ json_encode($course->ratingscales->map->only('id', 'name')) }}"
                display-field="name"
                ></input-multi-select>


            <button-submit label="{{__('t.global.add')}}">

                @component('components.help-text', ['id' => 'indicatorsHelp', 'key' => 't.views.admin.ratings.what_are_ratings'])@endcomponent

            </button-submit>

        @endcomponent

    </b-card>
    <b-card>
        <template #header>{{__('t.views.admin.ratings.existing', ['courseName' => $course->name])}}</template>

        @if (count($course->ratings))

            @php
                $fields = [
                    __('t.models.rating.name') => function(\App\Models\Rating $rating) { return $rating->name; },
                    // __('t.models.indicator.mandatory') => function(\App\Models\Requirement $requirement) { return $requirement->mandatory ? __('t.global.yes') : __('t.global.no'); },
                    // __('t.models.indicator.num_observations') => function(\App\Models\Indikator $indicator) { return count($indicator->observations); },
                ];

            @endphp
            @component('components.responsive-table', [
                'data' => $ratingscale->ratings,
                'fields' => $fields,
                'actions' => [
                    'edit' => function(\App\Models\Rating $rating) use ($course) { return route('admin.ratings.edit', ['course' => $course->id, 'rating' => $rating->id]); },
                    'delete' => function(\App\Models\Rating $rating) use ($course) { return [
                        'text' => __('t.views.admin.ratings.really_delete') ,
                        'route' => ['admin.ratings.delete', ['course' => $course->id, 'rating' => $rating->id]],
                    ];},
                ]
            ])@endcomponent

        @else

            {{__('t.views.admin.ratings.no_entries')}}

            @component('components.help-text', ['id' => 'noIndicatorsHelp', 'key' => 't.views.admin.ratings.are_ratings_required'])@endcomponent

        @endif

    </b-card>
@endsection
