@extends('layouts.default')

@section('content')

    @component('components.card', ['header' => __('t.views.admin.categories.new')])

        @component('components.form', ['route' => ['admin.categories.store', ['course' => $course->id]]])

            @component('components.form.textInput', ['name' => 'name', 'label' => __('t.models.category.name'), 'required' => true, 'autofocus' => true])@endcomponent

            @component('components.form.submit', ['label' => __('t.global.add')])

                @component('components.help-text', ['id' => 'categoryHelp', 'key' => 't.views.admin.categories.what_are_categories'])@endcomponent

            @endcomponent

        @endcomponent

    @endcomponent

    @component('components.card', ['header' => __('t.views.admin.categories.existing', ['courseName' => $course->name])])

        @if (count($course->categories))

            @php
                $fields = [
                    __('t.models.category.name') => function(\App\Models\Category $category) { return $category->name; },
                    __('t.models.category.num_observations') => function(\App\Models\Category $category) { return count($category->observations); },
                ];
                if ($course->archived) {
                    unset($fields[__('t.models.category.num_observations')]);
                }
            @endphp
            @component('components.responsive-table', [
                'data' => $course->categories,
                'fields' => $fields,
                'actions' => [
                    'edit' => function(\App\Models\Category $category) use ($course) { return route('admin.categories.edit', ['course' => $course->id, 'category' => $category->id]); },
                    'delete' => function(\App\Models\Category $category) use ($course) { return [
                        'text' => __('t.views.admin.categories.really_delete', ['name' => $category->name]) . ($course->archived ? '' : ' ' . trans_choice('t.views.admin.categories.observations_on_category', $category->observations)),
                        'route' => ['admin.categories.delete', ['course' => $course->id, 'category' => $category->id]],
                     ];},
                ]
            ])@endcomponent

        @else

            {{__('t.views.admin.categories.no_categories')}}

            @component('components.help-text', ['id' => 'noCategoriesHelp', 'key' => 't.views.admin.categories.are_categories_required'])@endcomponent

        @endif

    @endcomponent

@endsection