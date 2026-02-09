@extends('layouts.main')

@section('content')

    <div class="max-w-7xl mx-auto">

        <h2 class="text-3xl font-bold mb-6">Beat Assignment Logs</h2>

        {{-- LEGEND --}}
        <div class="flex gap-3 mb-6">
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-sm">✅ Strict</span>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">⚠ Dynamic</span>
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded text-sm">❌ Emergency</span>
        </div>

        {{-- FILTER BAR --}}
        <div class="bg-white border rounded-lg p-4 mb-6 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-2 items-center">

                <input type="hidden" name="date" value="{{ $date }}">

                <span class="font-semibold mr-2">Companies:</span>

                @foreach ([1 => 'HQ', 2 => 'A', 3 => 'B', 4 => 'C'] as $id => $name)
                    <button name="company_id" value="{{ $id }}"
                        class="px-3 py-1 rounded border {{ $companyId == $id ? 'bg-black text-white' : 'bg-gray-100' }}">
                        {{ $name }}
                    </button>
                @endforeach

                <button name="company_id" value=""
                    class="px-3 py-1 rounded border {{ !$companyId ? 'bg-black text-white' : 'bg-gray-100' }}">
                    All
                </button>

                <span class="mx-3 font-bold">|</span>

                <span class="font-semibold mr-2">Type:</span>

                <button name="reason" value="strict"
                    class="px-3 py-1 rounded {{ $reason == 'strict' ? 'bg-green-500 text-white' : 'bg-green-100' }}">
                    Strict
                </button>

                <button name="reason" value="dynamic"
                    class="px-3 py-1 rounded {{ $reason == 'dynamic' ? 'bg-yellow-500 text-white' : 'bg-yellow-100' }}">
                    Dynamic
                </button>

                <button name="reason" value="emergency"
                    class="px-3 py-1 rounded {{ $reason == 'emergency' ? 'bg-red-500 text-white' : 'bg-red-100' }}">
                    Emergency
                </button>

                <button name="reason" value=""
                    class="px-3 py-1 rounded border {{ !$reason ? 'bg-black text-white' : 'bg-gray-100' }}">
                    All
                </button>

            </form>
        </div>

        {{-- DAILY SUBTOTAL --}}
        <div class="bg-white border rounded-lg mb-6 shadow-sm overflow-hidden">

            <div class="px-4 py-2 bg-gray-50 font-semibold">
                Subtotal Assigned Per Day
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Students Assigned</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyCounts as $row)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $row->day }}</td>
                            <td class="px-4 py-2 font-bold">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-4 text-center text-gray-500">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- LOG TABLE --}}
        <div class="bg-white border rounded-lg shadow-sm overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2">S/N</th>
                        <th class="px-3 py-2">Student</th>
                        <th class="px-3 py-2">Platoon</th>
                        <th class="px-3 py-2">Area</th>
                        <th class="px-3 py-2">Reason</th>
                        <th class="px-3 py-2">Round</th>
                        <th class="px-3 py-2">Last Assigned</th>
                    </tr>
                </thead>
                <tbody>

                    @php $startSn = ($logs->currentPage()-1)*$logs->perPage(); @endphp

                    @forelse($logs as $i=>$log)
                        @php
                            $r = strtolower($log->reason);
                            $badge = str_contains($r, 'strict')
                                ? 'bg-green-100 text-green-700'
                                : (str_contains($r, 'dynamic')
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-red-100 text-red-700');

                            $area = $log->guardArea ?? $log->patrolArea;
                            $areaName = $area ? ($area->start_area ?? '') . ' - ' . ($area->end_area ?? '') : 'N/A';
                        @endphp

                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-3 py-2">{{ $startSn + $i + 1 }}</td>
                            <td class="px-3 py-2">{{ $log->student?->first_name }} {{ $log->student?->last_name }}</td>
                            <td class="px-3 py-2">{{ $log->student?->platoon }}</td>
                            <td class="px-3 py-2">{{ $areaName }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                                    {{ ucfirst($log->reason) }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $log->beat_round }}</td>
                            <td class="px-3 py-2">
                                {{ $log->last_assigned_at ? \Carbon\Carbon::parse($log->last_assigned_at)->format('Y-m-d H:i') : 'N/A' }}
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">No logs found</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>

@endsection
