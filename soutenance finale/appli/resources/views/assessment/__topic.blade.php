@extends('assessment/__model')
@section('topic')
    <h1>{{ $campaign->name }}</h1>
    
    <div class="bloc topic">
        <h2>Topic: {{ $topic->conversational }}</h2>
        <table>
            <tr>
                <td>Topic (id)</td>
                <td>{{ $topic->id }}</td>
            </tr>
            <tr>
                <td>Explanation</td>
                <td>{{ $topic->explanation }}</td>
            </tr>
            <tr>
                <td>Keywords</td>
                <td>{{ $topic->keywords }}</td>
            </tr>
        </table>
    </div>
@endsection
