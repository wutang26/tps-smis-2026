<div class="row">
    @if (session('info'))
        <script>
            // Trigger SweetAlert success message with session success
            Swal.fire({
                title: "{{ session('info') }}",  // Use the session success message
                icon: "info",                    // Set icon to 'success'
                draggable: true,                    // Enable dragging
            });
        </script>
    @endif
  </div>