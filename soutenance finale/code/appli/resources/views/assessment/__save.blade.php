@extends('assessment/__abstract')
@section('save')
<div class="save">
    {{-- Abandon --}}
    <form action="/dashboard" method="GET">
        <input type="submit" id="abandon" value="Abandon &amp; Back to dashboard" tabindex="-1" />
    </form>

    <div>
        {{-- Save and back dashboard --}}
        <form action="{{ route('save', [$assignation_id , 'dashboard']) }}" method="POST">
            @csrf

            <input type="hidden" name="data" class="data" value="">
            <input type="submit" id="saveBack" value="Save &amp; Back to dashboard" tabindex="-1" />
        </form>
        {{-- Save and next assessment --}}
        <form action="{{ route('save', [$assignation_id, 'assessment']) }}" method="POST">
            @csrf

            <input type="hidden" name="data" class="data" value="">
            <input
            @if (!$has_next)
            class="disabled"
            @endif
            type="submit"  id="saveNext" value="Save &amp; Next assessment" @if (!$has_next) disabled @endif tabindex="-1"/>
        </form>
    </div>
</div>
@endsection
