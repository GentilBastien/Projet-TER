@extends('dashboard/__campaign')
@section('adminTable')
    <div class="bloc table">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Experts</a></li>
                <li><a href="#tabs-2">Topics</a></li>
            </ul>
            <div id="tabs-1">
                <table class="ExpertsTable">
                    <thead>
                        <tr>
                            <th>Experts</th>
                            <th>Completed Assessments</th>
                            <th>Remaining Assessments</th>
                            <th>Total Assessments</th>
                        </tr>
                    </thead>
                    <tbody id="expertsTable">

                    </tbody>
                </table>
            </div>
            <div id="tabs-2">
                <table class="ExpertsTable">
                    <thead>
                        <tr>
                            <th>Topics</th>
                            <th>Completed Assessments</th>
                            <th>Remaining Assessments</th>
                            <th>Total Assessments</th>
                        </tr>
                    </thead>
                    <tbody id="topicsTable">

                    </tbody>
                </table>
            </div>
        @endsection
