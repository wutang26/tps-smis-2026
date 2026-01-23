<!-- Confirm Delete SweetAlert -->
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<script>
  function confirmDelete(formId, itemTitle) {
    // SweetAlert confirmation
    Swal.fire({
      title: 'Delete '+itemTitle,
      text: "This action cannot be undone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        // If confirmed, submit the form
        document.getElementById(formId).submit();
      }
    });
  }
</script>