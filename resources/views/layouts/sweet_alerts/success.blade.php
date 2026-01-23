<div class="row">

    @if (session('success'))
        <script>
            // Trigger SweetAlert success message with session success
            Swal.fire({
                title: "{{ session('success') }}",  // Use the session success message
                icon: "success",                    // Set icon to 'success'
                draggable: true,                    // Enable dragging
                timer: 1500,                        // Close the alert after 2 seconds
            });
        </script>
    @endif
  </div>