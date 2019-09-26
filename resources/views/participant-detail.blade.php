@extends('layouts.default')

@section('content')

    @component('components.card', ['header' => __('TN Details'), 'bodyClass' => 'container-fluid'])

        <div class="row my-3">

            <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
                <div class="square-container">
                    <img class="card-img-top img img-responsive full-width" src="{{ $participant->image_url != null ? asset(Storage::url($participant->image_url)) : asset('images/was-gaffsch.svg') }}" alt="{{ $participant->scout_name }}">
                </div>
            </div>

            <div class="col">
                <h3>{{ $participant->scout_name }}</h3>
                @if (isset($participant->group))<h5>{{ $participant->group }}</h5>@endif
                <p>{{ trans_choice('{0}Keine Beobachtungen|{1}1 Beobachtung|[2,*]:count Beobachtungen', count($participant->observations), ['count' => count($participant->observations)])}}, {{ __('davon :positive mit positivem, :neutral mit neutralem und :negative mit negativem Eindruck.', ['positive' => $participant->positive->count(), 'neutral' => $participant->neutral->count(), 'negative' => $participant->negative->count()])}}</p>
                @php
                    $columns = [];
                    foreach ($course->users->all() as $user) {
                        $columns[$user->name] = function($observations) use($user) { return count(array_filter($observations, function(\App\Models\Observation $observation) use($user) {
                            return $observation->user->id === $user->id;
                        })); };
                    }
                @endphp
                @component('components.responsive-table', [
                    'data' => [$participant->observations->all()],
                    'fields' => $columns,
                ])@endcomponent
                <a href="{{ route('observation.new', ['course' => $course->id, 'participant' => $participant->id]) }}" class="btn btn-primary"><i class="fas fa-binoculars"></i> {{__('Beobachtung erfassen')}}</a>
            </div>

        </div>

    @endcomponent

    @component('components.card', ['header' => __('Beobachtungen')])

        <div class="card">
            <div class="card-header" id="filters" data-toggle="collapse" data-target="#filters-collapse" aria-expanded="true" aria-controls="filters-collapse">
                <i class="fas fa-filter"></i> Filter
            </div>

            <div id="filters-collapse" class="collapse{{ $requirement !== null || $category !== null ? ' show' : '' }}" aria-labelledby="filters">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 col-sm-12">

                            <form id="requirement-form" method="GET" action="{{ route('participants.detail', ['course' => $course->id, 'participant' => $participant->id]) }}#filters">

                                <multi-select
                                  id="requirement"
                                  name="requirement"
                                  class="form-control-multiselect"
                                  value="{{ $requirement }}"
                                  :allow-empty="true"
                                  placeholder="Mindestanforderung"
                                  @php
                                    $jsonOptions = $course->requirements->map(function (App\Models\Requirement $requirement) {
                                        return [ 'label' => (string)$requirement->content, 'value' => (string)$requirement->id ];
                                    });
                                    $jsonOptions[] = [ 'label' => __('-- Beobachtungen ohne Mindestanforderungen --'), 'value' => '0' ];
                                  @endphp
                                  :options="{{ json_encode($jsonOptions) }}"
                                  :multiple="false"
                                  :close-on-select="true"
                                  :show-labels="false"
                                  submit-on-input="requirement-form"
                                  :show-clear="true"></multi-select>

                            </form>

                        </div>

                        <div class="col-md-6 col-sm-12">

                            <form id="category-form" method="GET" action="{{ route('participants.detail', ['course' => $course->id, 'participant' => $participant->id]) }}#filters">

                                <multi-select
                                  id="category"
                                  name="category"
                                  class="form-control-multiselect"
                                  value="{{ $category }}"
                                  :allow-empty="true"
                                  placeholder="Kategorie"
                                  @php
                                      $jsonOptions = $course->categories->map(function (App\Models\Category $category) {
                                          return [ 'label' => (string)$category->name, 'value' => (string)$category->id ];
                                      });
                                      $jsonOptions[] = [ 'label' => __('-- Beobachtungen ohne Kategorie --'), 'value' => '0' ];
                                  @endphp
                                  :options="{{ json_encode($jsonOptions) }}"
                                  :multiple="false"
                                  :close-on-select="true"
                                  :show-labels="false"
                                  submit-on-input="category-form"
                                  :show-clear="true"></multi-select>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        @if (count($observations))

            @component('components.responsive-table', [
                'data' => $observations,
                'rawColumns' => true,
                'fields' => [
                    __('Beobachtung') => function(\App\Models\Observation $observation) { return nl2br($observation->content); },
                    __('Block') => function(\App\Models\Observation $observation) { return $observation->block->blockname_and_number; },
                    __('MA') => function(\App\Models\Observation $observation) {
                        return implode('', array_map(function(\App\Models\Requirement $requirement) {
                            return '<span class="badge badge-' . ($requirement->mandatory ? 'warning' : 'info') . '" style="white-space: normal">' . $requirement->content . '</span>';
                        }, $observation->requirements->all()));
                    },
                    __('Eindruck') => function(\App\Models\Observation $observation) {
                        $impmression = $observation->impression;
                        if ($impmression === 0) return '<span class="badge badge-danger">negativ</span>';
                        else if ($impmression === 2) return '<span class="badge badge-success">positiv</span>';
                        else return '<span class="badge badge-secondary">neutral</span>';
                    },
                    __('Beobachter') => function(\App\Models\Observation $observation) { return $observation->user->name; }
                ],
                'actions' => [
                    'edit' => function(\App\Models\Observation $observation) use ($course) { return route('observation.edit', ['course' => $course->id, 'observation' => $observation->id]); },
                    'delete' => function(\App\Models\Observation $observation) use ($course) { return [
                        'text' => __('Willst du diese Beobachtung wirklich löschen?'),
                        'route' => ['observation.delete', ['course' => $course->id, 'observation' => $observation->id]],
                     ];},
                ]
            ])@endcomponent

        @else

            {{__('Keine Beobachtungen gefunden.')}}

        @endif

    @endcomponent

@endsection
