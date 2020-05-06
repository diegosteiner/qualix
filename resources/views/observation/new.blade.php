@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.observations.new')}}</template>

        @component('components.form', ['route' => ['observation.store', ['course' => $course->id]]])

            @component('components.form.multiSelectInput', [
                'name' => 'participants',
                'label' => __('t.models.observation.participants'),
                'required' => true,
                'value' => $participants,
                'options' => $course->participants->all(),
                'valueFn' => function(\App\Models\Participant $participant) { return $participant->id; },
                'displayFn' => function(\App\Models\Participant $participant) { return $participant->scout_name; },
                'multiple' => true,
                'autofocus' => ($participants === null)
            ])@endcomponent

            @component('components.form.textareaInput', ['name' => 'content', 'label' => __('t.models.observation.content'), 'required' => true, 'autofocus' => ($participants !== null)])@endcomponent

            <block-and-requirements-input-wrapper v-slot="slotProps" @if(old('requirements', 'OBSERVATION_NO_OLD_VALUES') !== 'OBSERVATION_NO_OLD_VALUES') :has-old-values="true" @endif>

                @component('components.form.multiSelectInput', [
                    'name' => 'block',
                    'label' => __('t.models.observation.block'),
                    'required' => true,
                    'value' => $block,
                    'options' => $course->blocks->all(),
                    'valueFn' => function(\App\Models\Block $block) { return $block->id; },
                    'displayFn' => function(\App\Models\Block $block) { return $block->blockname_and_number; },
                    'dataFn' => function(\App\Models\Block $block) { return implode(',', array_map(function(\App\Models\Requirement $requirement) { return $requirement->id; }, $block->requirements->all())); },
                    'multiple' => false,
                    'onInput' => 'slotProps.onBlockUpdate',
                ])@endcomponent

                @component('components.form.multiSelectInput', [
                    'name' => 'requirements',
                    'label' => __('t.models.observation.requirements'),
                    'valueBind' => 'slotProps.requirementsValue',
                    'options' => $course->requirements->all(),
                    'valueFn' => function(\App\Models\Requirement $requirement) { return $requirement->id; },
                    'displayFn' => function(\App\Models\Requirement $requirement) { return $requirement->content; },
                    'multiple' => true,
                ])@endcomponent

            </block-and-requirements-input-wrapper>

            @component('components.form.radioButtonInput', [
                'name' => 'impression',
                'label' => __('t.models.observation.impression'),
                'required' => true,
                'value' => '1',
                'options' => [ '2' => __('t.global.positive'), '1' => __('t.global.neutral'), '0' => __('t.global.negative')]
            ])@endcomponent

            @component('components.form.multiSelectInput', [
                'name' => 'categories',
                'label' => __('t.models.observation.categories'),
                'options' => $course->categories->all(),
                'valueFn' => function(\App\Models\Category $category) { return $category->id; },
                'displayFn' => function(\App\Models\Category $category) { return $category->name; },
                'multiple' => true,
            ])@endcomponent

            @component('components.form.submit', ['label' => __('t.global.save')])@endcomponent

        @endcomponent

    </b-card>

@endsection
