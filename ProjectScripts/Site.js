    $(document).ready(function(){
      //  var imgWidth = $('.img-container img').width();
      //  $('.img-container').width(imgWidth);
      $('.deleteAlbum').on('click', function(e){
        e.preventDefault();
        var deleteLink = $(this).attr('href');
        $.Zebra_Dialog("All pictures in the ablum will be delete with the ablum. Are you sure?", {
               type: 'warning',
               title: 'Warining Information',
               buttons: [
                   {caption: 'Confirm', callback: function(){
                           window.location.replace(deleteLink);
                   } },
                   { caption: 'Cancel' }
               ]
        });
    });
    
});

function ShowDiaglogBox(content){
    $.Zebra_Dialog(content, {
        type: 'information',
        title: 'Success Information',
        buttons:[{caption: 'Ok'}]
    });
}

