@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.requirements.new')}}</template>

        @component('components.form', ['route' => ['admin.requirements.store', ['course' => $course->id]]])

            @component('components.form.textInput', ['name' => 'content', 'label' => __('t.models.requirement.content'), 'required' => true, 'autofocus' => true])@endcomponent

            @component('components.form.checkboxInput', ['name' => 'mandatory', 'label' => __('t.models.requirement.mandatory')])@endcomponent

            @component('components.form.multiSelectInput', [
                'name' => 'blocks',
                'label' => __('t.models.requirement.blocks'),
                'options' => $course->blocks->all(),
                'valueFn' => function(\App\Models\Block $block) { return $block->id; },
                'displayFn' => function(\App\Models\Block $block) { return $block->name; },
                'multiple' => true,
            ])@endcomponent

            @component('components.form.submit', ['label' => __('t.global.add')])

                @component('components.help-text', ['id' => 'requirementsHelp', 'key' => 't.views.admin.requirements.what_are_requirements'])@endcomponent

            @endcomponent

        @endcomponent

    </b-card>

    <b-card>
        <template #header>{{__('t.views.admin.requirements.existing', ['courseName' => $course->name])}}</template>

        @if (count($course->requirements))

            @php
                $fields = [
                    __('t.models.requirement.content') => function(\App\Models\Requirement $requirement) { return $requirement->content; },
                    __('t.models.requirement.mandatory') => function(\App\Models\Requirement $requirement) { return $requirement->mandatory ? __('t.global.yes') : __('t.global.no'); },
                    __('t.models.requirement.num_observations') => function(\App\Models\Requirement $requirement) { return count($requirement->observations); },
                ];
                if ($course->archived) {
                    unset($fields[__('t.models.requirement.num_observations')]);
                }
            @endphp
            @component('components.responsive-table', [
                'data' => $course->requirements,
                'fields' => $fields,
                'actions' => [
                    'edit' => function(\App\Models\Requirement $requirement) use ($course) { return route('admin.requirements.edit', ['course' => $course->id, 'requirement' => $requirement->id]); },
                    'delete' => function(\App\Models\Requirement $requirement) use ($course) { return [
                        'text' => __('t.views.admin.requirements.really_delete') . ($course->archived ? '' : ' ' . trans_choice('t.views.admin.requirements.observations_on_requirement', $requirement->observations)),
                        'route' => ['admin.requirements.delete', ['course' => $course->id, 'requirement' => $requirement->id]],
                     ];},
                ]
            ])@endcomponent

        @else

            {{__('t.views.admin.requirements.no_requirements')}}

            @component('components.help-text', ['id' => 'noRequirementsHelp', 'key' => 't.views.admin.requirements.are_requirements_required'])@endcomponent

        @endif

    </b-card>

@endsection
