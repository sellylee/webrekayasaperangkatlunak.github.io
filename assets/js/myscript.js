const flashData = $('.flash-data').data('flashdata');

if(flashData){
    Swal.fire({
        title: 'Data', 
        text: 'Succeed ' + flashData,
        icon: 'success'
    });
}

// tombol hapus
$('.tombol-hapus').on('click', function (e) {

    e.preventDefault();
    const href = $(this).attr('href');
    
    Swal.fire({
        title: 'want u delete it?',
        text: "Data successfully deleted",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete data!'
      }).then((result) => {
        if (result.value) {
          document.location.href = href;
        }
      });
    
    })