@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.error_form.thank_you')}}</template>

        <p>{{__('t.views.error_form.error_report_has_been_submitted')}}</p>

        <a class="mb-1" href="{{ $previousUrl }}">
            {{ __('t.views.error_form.back') }}
        </a>

    </b-card>

@endsection
