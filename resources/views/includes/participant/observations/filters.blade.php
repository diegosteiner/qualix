<b-card id="filters" no-body>
    <b-card-header v-b-toggle.filters-collapse>
        <i class="fas fa-filter"></i> {{__('t.views.participant_details.filter')}}
    </b-card-header>

    <b-collapse id="filters-collapse" {{ $requirement !== null || $category !== null ? 'visible' : '' }}>

        <b-card-body>

            <b-row>

                <b-col cols="12" md="6">

                    <form id="requirement-form" method="GET" action="{{ route('participants.detail', ['course' => $course->id, 'participant' => $participant->id]) }}#filters">

                        <multi-select
                            id="requirement"
                            name="requirement"
                            class="form-control-multiselect"
                            value="{{ $requirement }}"
                            :allow-empty="true"
                            placeholder="{{__('t.views.participant_details.filter_by_requirement')}}"
                            :options="{{ json_encode($course->requirements->map->only('id', 'content')->concat([['content' => '-- ' . __('t.views.participant_details.observations_without_requirement') . ' --', 'id' => '0']])) }}"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            submit-on-input="requirement-form"
                            :show-clear="true"
                            display-field="content"></multi-select>

                    </form>

                </b-col>

                <b-col cols="12" md="6">

                    <form id="category-form" method="GET" action="{{ route('participants.detail', ['course' => $course->id, 'participant' => $participant->id]) }}#filters">

                        <multi-select
                            id="category"
                            name="category"
                            class="form-control-multiselect"
                            value="{{ $category }}"
                            :allow-empty="true"
                            placeholder="{{__('t.views.participant_details.filter_by_category')}}"
                            :options="{{ json_encode($course->categories->map->only('id', 'name')->concat([['name' => '-- ' . __('t.views.participant_details.observations_without_category') . ' --', 'id' => '0']])) }}"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            submit-on-input="category-form"
                            :show-clear="true"
                            display-field="name"></multi-select>

                    </form>

                </b-col>

            </b-row>
        </b-card-body>
    </b-collapse>
</b-card>
