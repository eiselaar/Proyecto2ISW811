<div class="card">
    <div class="card-header">Weekly Schedule</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Time</th>
                        @foreach($days as $day)
                            <th class="text-center">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($hours as $hour)
                        <tr>
                            <td>{{ sprintf('%02d:00', $hour) }}</td>
                            @foreach($days as $index => $day)
                                <td class="text-center">
                                    @if($hasScheduleAt($index, $hour))
                                        <i class="fas fa-check text-success"></i>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>