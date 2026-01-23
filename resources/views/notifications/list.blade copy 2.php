  <style>
  .notification-box:hover {
    cursor: pointer;
  }
</style>

 <div class="d-sm-flex d-none">
     <div class="dropdown">
         <a class="dropdown-toggle d-flex p-3 position-relative" href="#!" role="button" data-bs-toggle="dropdown"
             aria-expanded="false">
             <i class="bi bi-bell fs-4 lh-1 text-primary"></i>
             <span class="count-label">0</span>
         </a>
         <div class="dropdown-menu dropdown-menu-end dropdown-menu-sm">
             <h5 class="fw-semibold px-3 py-2 text-primary">Notifications</h5>
             <div class="scroll250">
                 <div class="mx-3 d-flex gap-2 flex-column">
                     <!-- <div class="bg-danger-subtle border border-danger px-3 py-2 rounded-1">
                                    <p class="m-0 text-danger">New product purchased</p>
                                    <p class="small m-0">Just now</p>
                                </div> -->

                 </div>
             </div>
             <div class="d-grid m-3">
                 <a id="viewAll" href="javascript:void(0)" class="btn btn-primary">View all</a>
             </div>
         </div>
     </div>

 </div>
 <!-- Notification script starts -->
 <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
 <!-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script> -->
 <script>
let notification_ids = "[";
// Enable pusher logging - don't include this in production
//Pusher.logToConsole = true;

@php
 $notifications = \App\Models\SharedNotification::unreadBy(auth()->id())->get();
 $unreadCount = $notifications->count();
@endphp
    // Convert PHP variable to JavaScript
    const notifications = @json($notifications);
    const unreadCount = @json($unreadCount);
    const counts = @json($unreadCount);
        updateNotificationCount(counts);
        notifications.forEach(notification => {
        appendNotification(notification);
    });
    // Show it using alert
    //alert(JSON.stringify(unreadCount));

var pusher = new Pusher('3a9b85e8ad0fb87a0a56', {
    cluster: 'mt1',
    encrypted: true, // Use encrypted connection (recommended)
    reconnection: true, // Enable automatic reconnection
    reconnectionAttempts: 5, // Max number of reconnection attempts
    reconnectionDelay: 1000, // Time in ms before each retry attempt
    reconnectTimeout: 5000, // Timeout before retrying reconnection
    authEndpoint: '/tps-smis/broadcasting/auth',  // ✅ Laravel default
    auth: {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  // ✅ Required if using web guard
        }
    }
});

//var userId = {{ auth()->user()->id }};
var channel = pusher.subscribe(`private-notifications.all`);
var companyChannel = pusher.subscribe(`private-notifications.company`);

function handleNotification(data) {
    console.log("Incoming notification:", data);
alert(data)
    const notification = data?.data;
    if (!notification || !data.id) return;

    // Update notification count
      updateNotificationCount(++counts);
      
      //appendNotification(notification);

    // Generate notification URL using a placeholder pattern
    
    // Append notification to dropdown
    
}

// Bind event to both channels
channel.bind('notification', handleNotification);
companyChannel.bind('notification', handleNotification);

// Monitor Pusher connection
pusher.connection.bind('state_change', function(states) {
    console.log('Pusher state changed:', states.previous, '→', states.current);

    if (states.current === 'disconnected') {
        // Reconnect manually if desired
        pusher.connect();
    }
});

// Handle errors
pusher.connection.bind('error', function(err) {
    console.error('Pusher connection error:', err);
});


function updateNotificationCount(count) {
  const countLabel = document.querySelector('.count-label');
  if (!countLabel) {
    console.warn('No element with class .count-label found.');
    return;
  }

  countLabel.textContent = count;

  if (count > 0) {
    countLabel.style.background = 'red';
    countLabel.style.color = 'white';
    countLabel.style.borderRadius = '50%';
    countLabel.style.minWidth = '20px';
    countLabel.style.textAlign = 'center';
    countLabel.style.fontSize = '0.8rem';
    countLabel.style.lineHeight = '20px';
    countLabel.style.position = 'absolute';
    countLabel.style.top = '10px';
    countLabel.style.right = '10px';
    countLabel.style.display = 'inline-block';
  } else {
    countLabel.style.display = 'none';
  }
}

function appendNotification(notification) {
    let url = '#'; // default if no category
    if (notification.notification_category_id) {
        url = `/tps-smis/notifications/showNotifications/${notification.notification_category_id}`;
    }

    const notificationsContainer = document.querySelector('.dropdown-menu .mx-3.d-flex.flex-column');
    if (!notificationsContainer) return;

    const notificationItem = document.createElement('div');
    notificationItem.className = 'notification-item';

notificationItem.innerHTML = `

<div class="notification-box bg-${notification.type ?? 'primary'}-subtle border border-${notification.type ?? 'primary'} px-3 py-2 rounded-1" 
     data-id="${notification.id}" 
     data-url="${url}"
     onclick="markNotificationAsRead(this)">
    <div class="dropdown-item text-${notification.type ?? 'primary'} d-flex align-items-center">
        ${notification.title}${notification.id}
    </div>
    <p class="small m-0 text-muted">${notification.created_at}</p>
</div>
`;



    notificationsContainer.prepend(notificationItem);
}

function markNotificationAsRead(element) {
    const notificationId = element.getAttribute('data-id');
    const url = element.getAttribute('data-url');
    fetch(`/tps-smis/notifications/mark-as-read/${notificationId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => {
        console.log(response)
        if (!response.ok) throw new Error('Failed to mark as read');
        window.location.href = url;
    })
    .catch(error => {
        console.error('Error marking as read:', error);
        window.location.href = url;
    });
}
 </script>