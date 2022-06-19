@extends('dashboard/__expertTable')

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Expert</title>
    <link rel="stylesheet" href="css/expertDashboard.css">
</head>

@section('expert')
    <script src="js/dashboard/view.js"></script>
    <script src="js/dashboard/controller.js"></script>
    <script>
        let topics = {!! $topicsAdvancement !!};
        updateExpertDashboard(topics);
    </script>

    <a
    @if ($has_next)
    href="/assessment"
    @else
    href="javascript:void(0);"
    @endif
    >
        <button type="button" class="greenButton">
            <img src="img/save.png" />
            <span @if (!$has_next) class="disabled" @endif>Start assessment</span>
        </button>
    </a>
@endsection
