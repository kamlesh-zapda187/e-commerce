/**************** 
 * custom modal 
 ***************/
function ajaxModal(title, url, modal_type='modal-md', id=''){
    $.ajax({
        "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        url: url,
        type: "post",
        dataType: "html",
        data: {'id':id},
        success: function (response) {
            if(response!='')
            {
                $('#commonModal .modal-title').html(title);
                $('#commonModal .modal-dialog').removeClass("modal-md");
                $('#commonModal .modal-dialog').removeClass("modal-lg");
                $('#commonModal .modal-dialog').removeClass("modal-sm");
                $('#commonModal .modal-dialog').addClass(modal_type);
                $('#commonModal .common_modal_content').html(response);
                $('#commonModal').modal('show');
            }
       },
     });	
}

/*************************************

Change Status

*************************************/

function changeStatus(btn, url, table, status, id)
{
   $.ajax({
      "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
      url: url,
      type: "POST",
      dataType: "json",
      data: {
         "update_id": id,
         "status": status,
         "table_name": table
      },
     
      success: function (result)
      {
         if (result.code == 1000 && result.code != '')
         {
            $.toast({
               heading: "Success",
               text: result.message,
               position: "top-right",
               loaderBg: "#5ba035",
               icon: "success"
            });

            if (status == 1) {
               $(btn).removeClass("bg-soft-success text-success");
               $(btn).addClass("bg-soft-danger text-danger");
               $(btn).html("Inactive");
               $(btn).attr("onclick", "changeStatus(this,'"+url+"','"+table+"',0," + id + ")");
            }

            if (status == 0) {
               $(btn).removeClass("bg-soft-danger text-danger");
               $(btn).addClass("bg-soft-success text-success");
               $(btn).html("Active");
               $(btn).attr("onclick", "changeStatus(this,'"+url+"','"+table+"',1," + id + ")");
            }
         } 
         else
         {
            $.toast({
               heading: "Error",
               text: result.message,
               position: "top-right",
               loaderBg: "#5ba035",
               icon: "error"
            });
         }
      },
      error:function(error)
      {
        $.toast({
            heading: "Error",
            text: 'Fail to changes ',
            position: "top-right",
            loaderBg: "#5ba035",
            icon: "error"
         }); 
      },
   });
}

/*************************************
Change Status End
*************************************/

/***
 * initiate dropify plugin
 */

let dropifyEvent = $(".dropify").dropify({
   messages:{
      default:"Drag and drop a file here or click",
      replace:"Drag and drop or click to replace",
      remove:"Remove",
      error:"Something went wrong, please try again"},
      error:{fileSize:"The uploaded image is too big. 1MB max.",
      imageFormat:"The image format is not allowed ({{ value }} only).",
         
   },
   //allowedFileExtensions: ['*'],
   imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'],
});

dropifyEvent.on('dropify.beforeClear', function(event, element){
   return confirm("Do you really want to remove Image?");
});