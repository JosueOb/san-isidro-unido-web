$(document).ready(function(){
    $(document).on('click', '.image-cancel', function() {
        let no = $(this).data('no');
        $(".gallery-item.item-"+no).remove();
    });
});
