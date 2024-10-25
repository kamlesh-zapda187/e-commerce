$(function(){
  
});

function submit_product_filters_frm(){
    $("#product_filters_frm").submit();
}

function searchProductByCategory(obj,category){
    $("#category").val(category);
    submit_product_filters_frm();
}

function getCurrentURL () {return window.location.href}
  
function add_to_cart(product_id,qty){
    $.ajax({
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        url: SITE_URL+"/product/add_to_cart",
        type: "post",
        dataType: "JSON",
        data: {
            product_id:product_id,
            qty:qty
        },
        success: function (response) {

            if(response.success){

                if(response.success){
                    $('.shopping .total-count').html(response.cart_total_items);
                    $( "#shopping-box" ).load(window.location.href + " #shopping-box" );
                    //$('.shopping-item').load(self);
                    //$(".shopping-box").remove();
                }

                Toast.fire({
                    icon: "success",
                    title: response.message
                  });
            }
            else{
                Toast.fire({
                    icon: "error",
                    title: response.message
                  });
            }

              
        }
    });    
}


