const Swal = require('sweetalert2')
$(document).ready(function () {
    //Se reseetea el valor del input cuando se refresque la página
    $('#icon').val('');

    $('#icon').on('change', function(event){
        $(this).removeClass('is-invalid');
        var image = event.target.files[0];
        let size = 1048576;//equivale a 1MB

        //se verifica que se haya seleccionado alguna imágen
        if (image) {
            //se verifica si el formato de la imagen
            if (/\.(jpe?g|png)$/i.test(image.name)) {
                //Se verifica si cumple con el tamaño
                if (image.size < size) {
                    //Se presenta en el input el nombre de la imagen
                    $(this).siblings(".custom-file-label").addClass("selected").html(image.name);

                    var reader = new FileReader();
                    reader.onload = function(){
                        var dataURL = reader.result;
                        
                        renderImage(dataURL);
                    };
                    reader.readAsDataURL(image);
                }else{
                    Swal.fire({
                        type: 'error',
                        title: 'Fuera del límite de 1MB',
                        text: 'La imagen ' + image.name + ' pesa ' + (image.size / 1048576).toFixed(2) + 'MB',
                    })
                };
            }else{
                $(this).val('');
                $(this).siblings(".custom-file-label").addClass("selected").html('');
                $('#icon').addClass('is-invalid');
                $('#icon').siblings('.invalid-feedback').html('<strong> Imagen no permitida </strong>');
                $('#gallery-images').html('');
            };
        }
        
    });
    const renderImage = image =>{
        let imageItem = `
        <div class="gallery-item">
            <img src=${image}>
        </div>
        `;
        $('#gallery-images').html(imageItem);
    }
});