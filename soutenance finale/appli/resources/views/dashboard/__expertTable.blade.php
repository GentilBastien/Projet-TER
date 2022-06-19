@extends('dashboard/__campaign')
@section('expertTable')
    <div class="bloc table">
        <table class="content" id="topicTable">
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
