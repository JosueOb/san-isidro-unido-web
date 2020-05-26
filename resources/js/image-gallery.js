const Swal = require('sweetalert2')

let numberOfImagesAllowed = 5;
let size = 1048576;//equivale a 1MB

var oldImages = [];
var newImages = [];
var imagesRender = [];

const previewImages = arrayImages => {
    let imageItem = '';
    var numberOfOldImages = 0;
    var numberOfNewImages = 0;

    arrayImages.forEach(function(image, index){
        for(var group in image){
            if(group === 'new'){
                image[group]['index'] = numberOfNewImages++;
            }
            if(group === 'old'){
                image[group]['index'] = numberOfOldImages++;
            }
            image[group]['position'] = index;

            imageItem += `
                <div class="gallery-item">
                <div class="image-cancel" id="delete_${group}_image" data-position="${image[group]['position']}" data-index="${image[group]['index']}">
                <i class="fas fa-trash-alt"></i>
                </div>
                <img src=${image[group]['src']} alt='image_${image[group]['index']}'>
                </div>
            `;
        }
    });

    if ($('#gallery-images').length != 0) {
        $('#gallery-images').html(imageItem);
    }
}

$('#images').on('change', function(event){
    $('#images').removeClass('is-invalid');

     //Se obtiene las imagenes del input
     var files = event.target.files;
     //se verifica que se haya seleccionado alguna imágen
     if (files) {
        //se recorre cada archivo para verificar que sea una imágen
        [].forEach.call(files, function (file, index) {

            if (/\.(jpe?g|png)$/i.test(file.name)) {
                //Si la imagen es menor a 1MB
                if (file.size < size) {
                    var reader = new FileReader();
                    reader.onload = (event) => {
                        if (imagesRender.length < numberOfImagesAllowed) {
                            newImages.push(file);

                            var imageRender = new Array();
                            var images = new Array();

                            imageRender['src'] = event.target.result;
                            images['new'] = imageRender;

                            imagesRender.push(images);

                            previewImages(imagesRender);
                            console.log(imagesRender.length)
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: 'Fuera del límite de imágenes seleccionadas',
                                text: 'Recuerda que solo puedes seleccionar hasta '+numberOfImagesAllowed+' imágenes',
                            })
                        }
                    }
                    reader.readAsDataURL(files.item(index));
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Fuera del límite de '+ ( size / 1048576) +'MB',
                        text: 'La imagen ' + file.name + ' pesa ' + (file.size / 1048576).toFixed(2) + 'MB',
                    })
                }
            } else {
                console.log('Archivo no permitidos');
                $('#images').addClass('is-invalid');
                $('#images').siblings('.invalid-feedback').html('<strong> Archivo no permitido </strong>');
            }
        });
    }
});

$('#gallery-images').on('click', '#delete_old_image', function () {
    let imageIndex = $(this).data('index');
    let imagePosition = $(this).data('position');
    oldImages.splice(imageIndex, 1);
    imagesRender.splice(imagePosition, 1);
    previewImages(imagesRender);
});

$('#gallery-images').on('click', '#delete_new_image', function () {
    let imageIndex = $(this).data('index');
    let imagePosition = $(this).data('position');
    newImages.splice(imageIndex, 1);
    imagesRender.splice(imagePosition, 1);
    previewImages(imagesRender);
});

function resetNumberOfImagesAllowed(number){
    numberOfImagesAllowed = number;
}
function resetImages(){
    //Se realiza la lectura de las imagenes que que encuentren en la sección de gallería
    var getImages = document.querySelectorAll("#gallery-images .gallery-item img");

    getImages.forEach(function (image, index) {
        var imageRender = new Array();
        var images = new Array();

        imageRender['src'] = image.src;
        images['old'] = imageRender;

        imagesRender.push(images);
        oldImages.push(image.dataset.image);
    });

    previewImages(imagesRender);
}

export{newImages, resetNumberOfImagesAllowed, resetImages, oldImages}