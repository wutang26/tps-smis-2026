<!-- Notification UI -->
<style>
  .notification-box:hover {
    cursor: pointer;
  }
  .count-label {
    position: absolute;
    top: 10px;
    right: 10px;
    min-width: 20px;
    height: 20px;
    border-radius: 50%;
    background: red;
    color: white;
    text-align: center;
    font-size: 0.8rem;
    line-height: 20px;
    display: inline-block;
  }
</style>

<div class="d-sm-flex d-none">
  <div class="dropdown">
    <a class="dropdown-toggle d-flex p-3 position-relative" href="#!" role="button" data-bs-toggle="dropdown">
      <i class="bi bi-bell fs-4 lh-1 text-primary"></i>
      <span class="count-label">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-sm">
      <h5 class="fw-semibold px-3 py-2 text-primary">Notifications</h5>
      <div class="scroll250">
        <div class="mx-3 d-flex gap-2 flex-column"></div>
      </div>
      <div class="d-grid m-3">
        <a id="viewAll" href="javascript:void(0)" class="btn btn-primary">View all</a>
      </div>
    </div>
  </div>
</div>

<!-- Laravel Echo -->
     <script src="/tps-smis/resources/assets/js/echo.iife.js"></script>
<script>
  @php
    $notifications = \App\Models\SharedNotification::unreadBy(auth()->id())->get();
    $unreadCount = $notifications->count();
  @endphp

  const notifications = @json($notifications);
  console.log(notifications)
  let counts = {!! json_encode($unreadCount) !!};
  updateNotificationCount(counts);
  notifications.forEach(appendNotification);

  const EchoConstructor = Echo?.default ?? Echo;

  class ReverbConnector {
    constructor(options = {}) {
      this.options = options;
      this.channels = {};
      this.socket = null;
      this.socket_id = null;
      this.pendingSubscriptions = [];
      this._connect();
    }

    _connect() {
      const protocol = this.options.scheme || (location.protocol === 'https:' ? 'wss' : 'ws');
      const host = this.options.host || 'localhost';
      const port = this.options.port || 6001;
      const wsUrl = `${protocol}://${host}:${port}/app/${this.options.appKey}?protocol=${this.options.protocol}&client=${this.options.client}&version=${this.options.version}&flash=false`;

      this.socket = new WebSocket(wsUrl);

      this.socket.onopen = () => {
        console.log('✅ WebSocket connected to', wsUrl);
      };

      this.socket.onmessage = (event) => {
        try {
          const data = JSON.parse(event.data);
          //console.log('Raw WebSocket message:', data);

          if (data.event === 'pusher:connection_established') {
            const payload = JSON.parse(data.data);
            this.socket_id = payload.socket_id;
            //console.log('🔌 socket_id:', this.socket_id);

            Object.keys(this.channels).forEach(channel => {
              this._subscribeChannel(channel);
            });

            this.pendingSubscriptions.forEach(fn => fn());
            this.pendingSubscriptions = [];
          }

          if (
            data.channel &&
            data.event &&
            this.channels[data.channel] &&
            typeof this.channels[data.channel][data.event] === 'function'
          ) {
            // Sometimes payload is a JSON string, parse it safely
            let payload = data.data || data.payload;
            if (typeof payload === 'string') {
              try {
                payload = JSON.parse(payload);
              } catch {
                // If not JSON, leave as is
              }
            }

            //console.log(`Calling handler for event: ${data.event}`, 'Payload:', payload);
            this.channels[data.channel][data.event](payload);
          }
        } catch (err) {
          console.error('❌ Error parsing WebSocket message:', err);
        }
      };

      this.socket.onerror = (err) => {
        console.error('🚨 WebSocket error:', err);
      };

      this.socket.onclose = () => {
        console.warn('❌ WebSocket connection closed');
        this.socket_id = null;
      };
    }

    _subscribeChannel(channel) {
      if (channel.startsWith('private-')) {
        fetch(this.options.authEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            ...(this.options.auth?.headers || {}),
          },
          credentials: 'same-origin',
          body: JSON.stringify({
            channel_name: channel,
            socket_id: this.socket_id,
          }),
        })
          .then(res => {
            if (!res.ok) throw new Error('Auth failed');
            return res.json();
          })
          .then(authData => {
            this.socket.send(JSON.stringify({
              event: 'pusher:subscribe',
              data: {
                channel: channel,
                auth: authData.auth || authData,
              },
            }));
            console.log(`🔐 Subscribed to private channel: ${channel}`);
          })
          .catch(err => console.error('❌ Auth error:', err));
      } else {
        this.socket.send(JSON.stringify({
          event: 'pusher:subscribe',
          data: { channel: channel },
        }));
        //console.log(`📡 Subscribed to channel: ${channel}`);
      }
    }

    listen(channel, event, callback) {
      if (!this.channels[channel]) {
        this.channels[channel] = {};
      }

      this.channels[channel][event] = callback;

      if (this.socket.readyState === WebSocket.OPEN && this.socket_id) {
        this._subscribeChannel(channel);
      } else {
        this.pendingSubscriptions.push(() => this._subscribeChannel(channel));
      }

      return this;
    }

    stopListening(channel, event) {
      if (this.channels[channel]) {
        delete this.channels[channel][event];
      }
    }

    channel(name) {
      return { listen: (event, callback) => this.listen(name, event, callback) };
    }

    privateChannel(name) {
      return this.channel(`private-${name}`);
    }

    private(name) {
      return this.privateChannel(name);
    }

    join() { return this; }
    here() { return this; }
    error() { return this; }
  }

  window.Echo = new EchoConstructor({
    broadcaster: 'null',
    authEndpoint: '/tps-smis/broadcasting/auth',
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
    },
  });

  window.Echo.connector = new ReverbConnector({
    authEndpoint: '/tps-smis/broadcasting/auth',
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
    },
    appKey: 'local',
    host: '192.168.16.227',
    port: 6001,
    scheme: 'ws',
    client: 'js',
    version: '7.0.3',
    protocol: 7,
  });

  const notificationsChannel = window.Echo.private('notifications.all');
  const companyChannel = window.Echo.private('notifications.company');

  function handleNotification(data) {
    console.log(data);
    if (!data || !data.title) {
      console.warn('Invalid notification data or missing title:', data);
      return;
    }
    counts++;
    updateNotificationCount(counts);
    appendNotification(data);
  }

  notificationsChannel.listen('notification', handleNotification);
  companyChannel.listen('notification', handleNotification);

  function updateNotificationCount(count) {
    const label = document.querySelector('.count-label');
    if (!label) return;
    label.textContent = count;
    label.style.backgroundColor = 'red';
    label.style.display = count > 0 ? 'inline-block' : 'none';
  }

  function appendNotification(notification) {
    console.log(notification.id)
    const title = notification.title;
    const type = '';
    const id = '';
    const created_at = '';
    const category = notification.notification_category_id;
    const shared_id = JSON.stringify(notification.id);
        const url = notification.notification_category_id
      ? `/tps-smis/notifications/showNotifications/${JSON.stringify(notification.data.id)}/${category}`
      : '#';
if (typeof notification.data !== 'undefined') {
  notification = notification.data; // extract the real object, not stringify yet
}

    const container = document.querySelector('.dropdown-menu .mx-3.d-flex.flex-column');
    if (!container) return;

    const div = document.createElement('div');
    div.className = 'notification-item';
    const date = new Date(notification.created_at);
    const formatted_date = `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')} ${date.getDate()} ${date.toLocaleString('default', { month: 'long' })}, ${date.getFullYear()}`;

    div.innerHTML = `
      <div
        class="notification-box bg-${notification.type ?? 'primary'}-subtle border border-${notification.type ?? 'primary'} px-3 py-2 rounded-1"
        data-id="${shared_id}"
        data-url="${url}"
        onclick="markNotificationAsRead(this)"
      >
        <div class="dropdown-item text-${notification.type ?? 'primary'} d-flex align-items-center">
          ${title}
        </div>
        <p class="small m-0 text-muted">${formatted_date}</p>
      </div>
    `;

    container.prepend(div);
  }

  function markNotificationAsRead(element) {
    const id = element.getAttribute('data-id');
    const url = element.getAttribute('data-url');
    fetch(`/tps-smis/notifications/mark-as-read/${id}`, {
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        Accept: 'application/json',
      },
    })
      .then((res) => {
        if (!res.ok) throw new Error('Failed to mark as read');
        window.location.href = url;
      })
      .catch((err) => {
        console.error('Read error:', err);
        window.location.href = url;
      });
  }
</script>
