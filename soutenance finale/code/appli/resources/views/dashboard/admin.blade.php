@extends('dashboard/__adminTable')

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="css/expertDashboard.css">
    <link rel="stylesheet" href="css/adminDashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
</head>

@section('admin')
    <script src="js/dashboard/view.js"></script>
    <script src="js/dashboard/controller.js"></script>
    <script>
        let experts = {!! $expertsAdvancement !!};
        let topics = {!! $topicsAdvancement !!};
        updateAdminDashboard(experts, topics);
    </script>

    <div class="bloc buttons">
        {{-- Export completed assessments --}}
        <form action="{{ route('export', 'completed') }}" id="exportCompleted" method="POST">
            @csrf
            <input type="submit" value="Export completed assessments" />
        </form>

        {{-- Export remaining assessments --}}
        <form action="{{ route('export', 'remaining') }}" id="exportRemaining" method="POST">
            @csrf
            <input type="submit" value="Export remaining assessments" />
        </form>

        {{-- Add experts --}}
        <form action="{{ route('add', 'experts') }}" method="POST" id="addExperts">
            @csrf
            <label for="input_expert">Add experts</label>
            <input type="hidden" id="content_expert" name="content_expert" value="">
            <input id="input_expert" type="file" accept=".txt" onchange="readText(event,'content_expert','addExperts')" />
        </form>

        {{-- Add assessments --}}
        <form action="{{ route('add', 'assessments') }}" method="POST" id="addAssessments">
            @csrf
            <label for="input_assessment">Add assessments</label>
            <input type="hidden" id="content_assessment" name="content_assessment" value="">
            <input id="input_assessment" type="file" accept=".txt"
                onchange="readText(event,'content_assessment','addAssessments')" />
        </form>

        <script>
            $(function() {
                $("#tabs").tabs();
            });
            async function readText(event, DOM_text, DOM_form) {
                const file = event.target.files.item(0);
                const text = await file.text();
                document.getElementById(DOM_text).value = text;
                document.getElementById(DOM_form).submit();
            }
        </script>
    </div>
@endsection
