<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            max-width: 500px;
            padding: 20px;
            background: #fff;
            border: 1px solid #dee2e6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .heading {
            font-size: 24px;
            color: #e63946;
            margin-bottom: 10px;
        }
        .message {
            font-size: 18px;
            color: #495057;
        }
        .back-link {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>404</h1>
    <p>Sorry, the page you are looking for could not be found.</p>
    <p><a href="{{ url('/') }}">Go back to home</a></p>
    </div>
</body>
</html>
