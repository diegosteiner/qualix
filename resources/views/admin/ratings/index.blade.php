@extends('layouts.default')

@section('content')

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

                @component('components.help-text', ['id' => 'categoryHelp', 'key' => 't.views.admin.ratings.what_are_ratings'])@endcomponent

            </button-submit>

        @endcomponent

    </b-card>

    <b-card>
        <template #header>{{__('t.views.admin.ratings.existing', ['courseName' => $course->name])}}</template>

        @if (count($course->ratings))

            @php
                $fields = [
                    __('t.models.rating.name') => function(\App\Models\Rating $rating) { return $rating->name; },
                    __('t.models.rating.ratingscale') => function(\App\Models\Rating $rating) { return $rating->ratingscale->name; },
                ];
            @endphp
            @component('components.responsive-table', [
                'data' => $course->ratings,
                'fields' => $fields,
                'actions' => [
                    'edit' => function(\App\Models\Rating $rating) use ($course) { return route('admin.ratings.edit', ['course' => $course->id, 'rating' => $rating->id]); },
                    'delete' => function(\App\Models\Rating $rating) use ($course) { return [
                        'text' => __('t.views.admin.ratings.really_delete', ['name' => $rating->name]),
                        'route' => ['admin.ratings.delete', ['course' => $course->id, 'rating' => $rating->id]],
                     ];},
                ]
            ])@endcomponent

        @else

            {{__('t.views.admin.ratings.no_entries')}}

            @component('components.help-text', ['id' => 'noCategoriesHelp', 'key' => 't.views.admin.ratings.are_ratings_required'])@endcomponent

        @endif

    </b-card>

@endsection
