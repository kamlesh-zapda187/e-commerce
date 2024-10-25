const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });

  function remove_product_from_cart(obj,cart_id){
    $.ajax({
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        url: SITE_URL+"/product/remove_product_from_cart",
        type: "post",
        dataType: "JSON",
        data: {
            cart_id:cart_id,
        },
        success: function (response) {
            
            location.reload();
        }
    });    
}