@extends('assessment/__save')
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assessment</title>
    <link rel="stylesheet" href="css/annotations.css">
    <link rel="stylesheet" href="css/assessment.css">
</head>

@section('assessment')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/assessment/view.js"></script>
<script src="js/assessment/controller.js"></script>
@endsection
