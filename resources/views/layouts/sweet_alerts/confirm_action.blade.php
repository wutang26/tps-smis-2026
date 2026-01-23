<!-- Confirm Delete SweetAlert -->
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<script>
  function confirmAction(formId, itemTitle, message, action) {
    // SweetAlert confirmation
    Swal.fire({
      title: itemTitle,
      text: "Are sure you want to " + action  +' '+message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, '+action,
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
