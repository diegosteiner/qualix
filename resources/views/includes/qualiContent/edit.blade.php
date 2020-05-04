@component('components.form', ['route' => ['qualiContent.update', ['course' => $course->id, 'participant' => $participant->id, 'quali' => $quali->id]]])
    <div class="mb-3">
        <h5>{{__('t.views.quali_content.requirements_status')}}</h5>
        @component('components.requirement-progress', ['quali' => $quali])@endcomponent
    </div>

    <div class="d-flex justify-content-between mb-2">
        <button type="submit" class="btn btn-primary">{{__('t.global.save')}}</button>
        <div><a class="btn-link" href="#">Alle Anforderungen einklappen</a></div>
    </div>

    @error('contents')
        <div class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </div>
    @enderror

    <list-builder
        name="contents"
        :value="{{ old('contents') ?? json_encode($quali->contents) }}"
        :translations="{{ json_encode($translations) }}">

        <template v-slot:text="{ value, remove, translations }"><element-text v-model="value" :remove="remove" :translations="translations" /></template>

        <template v-slot:observation="{ value, remove, translations }"><element-observation v-model="value" :remove="remove" :translations="translations" /></template>

        <template v-slot:requirement="{ value, translations }"><element-requirement v-model="value" :translations="translations">

            <list-builder :value="value.contents" :translations="translations">

                <template v-slot:text="{ value, remove, translations }"><element-text v-model="value" :remove="remove" :translations="translations" /></template>

                <template v-slot:observation="{ value, remove, translations }"><element-observation v-model="value" :remove="remove" :translations="translations" /></template>

                <template v-slot:add-text="{ addElement }">
                    <button-add @click="addElement" :payload="{{ json_encode(['type' => 'text','content' => '', 'id' => null]) }}">{{__('t.views.quali_content.text_element')}}</button-add>
                </template>

            </list-builder>

        </element-requirement></template>

        <template v-slot:add-text="{ addElement }">
            <button-add @click="addElement" :payload="{{ json_encode(['type' => 'text','content' => '', 'id' => null]) }}">{{__('t.views.quali_content.text_element')}}</button-add>
        </template>

    </list-builder>
@endcomponent