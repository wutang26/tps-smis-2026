<table>
  <thead>
    <tr>
      <th>Region</th>
      <th>Force Number</th>
      <th>Rank</th>
      <th>Full Name</th>
      <th>Designation</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($grouped as $regionName => $staffGroup)
      @foreach($staffGroup as $member)
        <tr>
          <td>{{ $regionName }}</td>
          <td>{{ $member->forceNumber }}</td>
          <td>{{ $member->rank }}</td>
          <td>{{ $member->firstName }} {{ $member->middleName }} {{ $member->lastName }}</td>
          <td>{{ $member->designation }}</td>
          <td>{{ ucfirst($member->status) }}</td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>
