@extends('__logout')
@section('campaign')
    <script src="js/dashboard/model.js"></script>
    <div class="bloc campagne">
        <div>
            <h1>TREC Robust 2022</h1>
        </div>
        <div>
            <span class="fade">Assessment type</span>
            <span>{{ $campaign->getType() == "globalwords" ? "global/words" : $campaign->getType() }}</span>
        </div>
        <div>
            <table>
                <tr>
                    <td>
                        <span class="fade">Assessments</span>
                    </td>
                    <td class="content">
                        <span class="fade">Completed</span><br>
                        <span>
                            @if ($type == 'expert')
                                {{ $user->getNbCompletedAssessments() }}
                            @else
                                {{ App\Models\Assignation::getNbCompletedAssessments() }}
                            @endif
                        </span>
                    </td>
                    <td class="content">
                        <span class="fade">Remaining</span><br>
                        <span>
                            @if ($type == 'expert')
                                {{ $user->getNbRemainingAssessments() }}
                            @else
                                {{ App\Models\Assignation::getNbRemainingAssessments() }}
                            @endif
                        </span>
                    </td>
                    <td class="content">
                        <span class="fade">Total</span><br>
                        <span>
                            @if ($type == 'expert')
                                {{ $user->getNbAssessments() }}
                            @else
                                {{ App\Models\Assignation::getNbAssessments() }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="fade">Topics</span>
                    </td>
                    <td class="content">
                        <span class="fade">Completed</span><br>
                        <span>
                            @if ($type == 'expert')
                                {{ $user->getNbCompletedTopics() }}
                            @else
                                {{ App\Models\Topic::getNbCompletedTopics() }}
                            @endif
                        </span>
                    </td>
                    <td class="content">
                        <span class="fade">Remaining</span><br>
                        <span>
                            @if ($type == 'expert')
                                {{ $user->getNbRemainingTopics() }}
                            @else
                                {{ App\Models\Topic::getNbRemainingTopics() }}
                            @endif
                        </span>
                    </td>
                    <td class="content">
                        <span class="fade">Total</span><br>
                        <span>
                            @if ($type == 'expert')
                                {{ $user->getNbTopics() }}
                            @else
                                {{ App\Models\Topic::getNbTopics() }}
                            @endif
                        </span>
                    </td>
                </tr>
                @if ($type == 'admin')
                <tr>
                    <td>
                        <span class="fade">Expert's work</span>
                    </td>
                    <td class="content">
                        <span class="fade">Completed</span><br>
                        <span>
                            {{ App\Models\Expert::getNbCompletedTasks() }}
                        </span>
                    </td>
                    <td class="content">
                        <span class="fade">Remaining</span><br>
                        <span>
                            {{ App\Models\Expert::getNbRemainingTasks() }}
                        </span>
                    </td>
                    <td class="content">
                        <span class="fade">Total</span><br>
                        <span>
                            {{ App\Models\Expert::getNbTasks() }}
                        </span>
                    </td>
                </tr>
                @endif
            </table>
        </div>
        <div>
            <span id="detailedInstrutionsLink">
                <a href="{{"https://" . $campaign->getDetailedInstructions() }}" target="_blank">Detailed instructions</a>
            </span>
        </div>
    </div>
@endsection
