<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Publish an event to channel <code>my-channel</code>
    with event name <code>my-event</code>; it will appear below:
  </p>
  <div id="app">
    <ul>
        Helo
    </ul>
  </div>

  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('3a9b85e8ad0fb87a0a56', {
      cluster: 'mt1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        alert(JSON.stringify(data))
      app.messages.push(JSON.stringify(data));
    });


  </script>
</body>
