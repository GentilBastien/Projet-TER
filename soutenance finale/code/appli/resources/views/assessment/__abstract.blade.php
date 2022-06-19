@extends('assessment/__instructions')
@section('abstract')
    <div class="bloc snippet">
        <div id="snippetId">
            @if ($campaign->getTarget() == 'snippets')
            Snippet - {{ $assessment->title }}
            @else
                @if (isset($assessment->internal_uri))
                Document - {{ $assessment->internal_uri }}
                @else
                Document - {{ $assessment->external_url }}
                @endif
            @endif
            </div>
        <div>
            <p id="abstract">

            </p>
        </div>

        <div id="globalRelevance">
            <span>Global relevance</span>

            {{-- The global tags buttons --}}
            @foreach ($globaltags as $globaltag)
                <div @if ($globaltag->value < 0) class="negative" @endif>
                    <input class="radioButton"
                    type="radio" name="global" id="{{ $globaltag->tag_name }}">
                    <label id="radioLabels" for="{{ $globaltag->tag_name }}">{{ $globaltag->tag_name }}</label>
                </div>
            @endforeach

        </div>
    </div>
@endsection
