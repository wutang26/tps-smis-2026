<!DOCTYPE html>
<html>
<head>
    <title>Timetable PDF</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #004085; background-color: #cce5ff; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid black; padding: 10px; text-align: center; }
        th { background-color: #343a40; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; } 
        .time-slot { font-weight: bold; background-color: #004085; color: white; }
    </style>
</head>
<body>
    <h1>Weekly Timetable - {{ $selectedCompany }} Company</h1>
    <table>
        <thead>
            <tr>
                <th class="time-slot">Time Slot</th>
                @foreach ($daysOfWeek as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($timeSlots as $timeSlot)
                <tr>
                    <td class="time-slot"><strong>{{ $timeSlot }}</strong></td>
                    @foreach ($daysOfWeek as $day)
                        @php
                            $entry = $structuredTimetable[$timeSlot][$day] ?? null;
                        @endphp
                        <td>
                            @if ($entry)
                                <strong>{{ $entry->activity }}</strong><br>
                                <strong>Venue:</strong> {{ $entry->venue }}<br>
                                @if ($entry->time_slot !== '10:00 AM - 11:00 AM' && $entry->time_slot !== '1:00 PM - 2:00 PM')

                                 <strong>Instructor:</strong> {{ $entry->instructor }}
                                 @endif
                            @else
                                <span class="text-muted">No Scheduled Activity</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
