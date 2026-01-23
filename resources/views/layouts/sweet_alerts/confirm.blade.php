<script>
  function confirmReturn(title,message,action) {
    Swal.fire({
      title: title,
      text: message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, '+action,
      cancelButtonText: 'No, cancel',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        // You can perform any action here — like redirecting or logging
       // Swal.fire('Returned!', 'The results have been returned.', 'success');
      }
    });
  }
</script>