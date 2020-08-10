@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.observations.new')}}</template>

        @component('components.form', ['route' => ['observation.store', ['course' => $course->id]]])

            <input-multi-select
                name="participants"
                value="{{ $participants }}"
                label="{{__('t.models.observation.participants')}}"
                :options="{{ json_encode($course->participants->map->only('id', 'scout_name')) }}"
                display-field="scout_name"
                multiple
                required
                :autofocus="{{ $participants === null ? 'true' : 'false' }}"></input-multi-select>

            <input-textarea
                name="content"
                label="{{__('t.models.observation.content')}}"
                required
                :autofocus="{{ ($participants !== null) ? 'true' : 'false' }}"></input-textarea>

            <block-and-requirements-input-wrapper
                v-slot="{ onBlockUpdate, requirementsValue }"
                initial-requirements-value="{{ old('requirements') }}"
                :block-requirements-mapping="{{ json_encode($course->blocks->map->only('id', 'requirement_ids')) }}">

                <input-multi-select
                    name="block"
                    value="{{ $block }}"
                    label="{{__('t.models.observation.block')}}"
                    required
                    :options="{{ json_encode($course->blocks->map->only('id', 'blockname_and_number', 'requirement_ids')) }}"
                    display-field="blockname_and_number"
                    @input="onBlockUpdate"></input-multi-select>

                <input-multi-select
                    name="requirements"
                    :value="requirementsValue"
                    error-message="{{ $errors->first('requirements') }}"
                    label="{{__('t.models.observation.requirements')}}"
                    :options="{{ json_encode($course->requirements->map->only('id', 'content')) }}"
                    display-field="content"
                    multiple></input-multi-select>

            </block-and-requirements-input-wrapper>

            <input-radio-button
                name="impression"
                value="1"
                label="{{__('t.models.observation.impression')}}"
                required
                :options="{{ json_encode([ '2' => __('t.global.positive'), '1' => __('t.global.neutral'), '0' => __('t.global.negative')]) }}"></input-radio-button>

            <input-multi-select
                name="categories"
                label="{{__('t.models.observation.categories')}}"
                :options="{{ json_encode($course->categories->map->only('id', 'name')) }}"
                display-field="name"
                multiple></input-multi-select>

            <button-submit></button-submit>

        @endcomponent

    </b-card>

@endsection
