<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Details</title>
</head>
<body>
    <h1>{{ $announcement->title }}</h1>
    <p>{{ $announcement->message }}</p>
    <p>Type: {{ $announcement->type }}</p>
    <p>Expires At: {{ $announcement->expires_at }}</p>
    <a href="{{ route('announcements.index') }}">Back to Announcements</a>
</body>
</html>
