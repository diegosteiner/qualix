@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.ratingscales.new')}}</template>

        @component('components.form', ['route' => ['admin.ratingscales.store', ['course' => $course->id]]])

            <input-text @forminput('name') label="{{__('t.models.ratingscale.name')}}" required autofocus></input-text>

            <button-submit label="{{__('t.global.add')}}">

                @component('components.help-text', ['id' => 'categoryHelp', 'key' => 't.views.admin.ratingscales.what_are_ratingscales'])@endcomponent

            </button-submit>

        @endcomponent

    </b-card>

    <b-card>
        <template #header>{{__('t.views.admin.ratingscales.existing', ['courseName' => $course->name])}}</template>

        @if (count($course->ratingscales))

            @php
                $fields = [
                    __('t.models.ratingscale.name') => function(\App\Models\Ratingscale $ratingscale) { return $ratingscale->name; },
                    __('t.models.ratingscale.num_ratings') => function(\App\Models\Ratingscale $ratingscale) { return count($ratingscale->ratings); },
                ];
            @endphp
            @component('components.responsive-table', [
                'data' => $course->ratingscales,
                'fields' => $fields,
                'actions' => [
                    'edit' => function(\App\Models\Ratingscale $ratingscale) use ($course) { return route('admin.ratingscales.edit', ['course' => $course->id, 'ratingscale' => $ratingscale->id]); },
                    'delete' => function(\App\Models\Ratingscale $ratingscale) use ($course) { return [
                        'text' => __('t.views.admin.ratingscales.really_delete', ['name' => $ratingscale->name]),
                        'route' => ['admin.ratingscales.delete', ['course' => $course->id, 'ratingscale' => $ratingscale->id]],
                    ];},
                ]
            ])@endcomponent

        @else

            {{__('t.views.admin.ratingscales.no_entries')}}

            @component('components.help-text', ['id' => 'noCategoriesHelp', 'key' => 't.views.admin.ratingscales.are_ratingscales_required'])@endcomponent

        @endif

    </b-card>

@endsection
