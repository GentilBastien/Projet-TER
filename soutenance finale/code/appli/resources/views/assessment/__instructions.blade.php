@extends('assessment/__topic')
@section('instructions')
<div class="bloc instructions">
    <table>
        <tr>
            @if (str_contains($campaign->type, 'words'))
            <td>Words Instructions</td>
            <td>{{ $campaign->abbreviate_instructions_words }}</td>
            <td>
                {{-- we skip first wordtag because it is not reviewed --}}
                @for ($i = 1; $i < count($wordtags); $i++) 
                @php 
                $style="background-color: " . $wordtags[$i]->color . ";";
                @endphp
                <span style="{{$style}}">{{ $wordtags[$i]->tag_name }}</span>
                @endfor
            </td>
            @endif
        </tr>
        <tr>
            @if (str_contains($campaign->type, 'global'))
            <td>Global Instructions</td>
            <td>{{ $campaign->abbreviate_instructions_global }}</td>
            <td>
                @foreach ($globaltags as $tag)
                @if($tag->value >= 0)
                <span>{{ $tag->tag_name }}</span>
                @endif
                @endforeach
            </td>
            @endif
        </tr>
    </table>
    <a href="" tabindex="-1">Detailed Instructions</a>
</div>
@endsection