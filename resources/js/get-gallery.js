const Swal = require('sweetalert2')
$(document).ready(function(){

    console.log('documento listo....');
    //Se realiza la lectura de las imagenes que que encuentren en la sección de gallería
    var getImagesReport = document.querySelectorAll("#gallery-update .gallery-item img");
    var urlImagesReport = [];
    var inputImages = [];
    var imagesRender = [];

    getImagesReport.forEach(function(image, index){
        var imageRender = new Array();
        var images = new Array();

        imageRender['src'] = image.src;
        images['report'] = imageRender;

        imagesRender.push(images);
        urlImagesReport.push(image.dataset.image);
    });
    
    const previewImages = images =>{
        let imageItem = '';
        
        images.forEach(function(image, indice){
            for (var group in image){
                if(group === 'report'){
                    image[group]['index'] = urlImagesReport.length - 1;
                }
                if(group === 'input'){
                    image[group]['index'] = inputImages.length - 1;
                }
                image[group]['position'] = indice;

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
        
        var gallery = document.getElementById('gallery-update');
        if(gallery){
            gallery.innerHTML = imageItem;
        }
    }
    
    previewImages(imagesRender);
    
    //Al seleccionar el input file
     $('#inputImages').on('change', function(event){
        $('#images').removeClass('is-invalid');
        //Se obtiene las imagenes del input
        var files = event.target.files;
        let numberOfImagesAllowed = 5;
        let size = 1048576;//equivale a 1MB
        
        //se verifica que se haya seleccionado alguna imágen
        if(files){
            //se recorre cada archivo para verificar que sea una imágen
            [].forEach.call(files, function(file, index){
                if(imagesRender.length < numberOfImagesAllowed){
                    console.log('Seleccionó una imagen');
                    if ( /\.(jpe?g|png)$/i.test(file.name) ) {
                        //Si la imagen es menor a 1MB
                        if(file.size < size){
                            inputImages.push(file);
                            var reader = new FileReader();
                            reader.onload = function(event){
                                var imageRender = new Array();
                                var images = new Array();
                                
                                imageRender['src'] = event.target.result; 
                                images['input'] = imageRender;

                                imagesRender.push(images); 
                                previewImages(imagesRender);
                            }
                            reader.readAsDataURL(files.item(index));
                        }else{
                            Swal.fire({
                                type: 'error',
                                title: 'Fuera del límite de 1MB',
                                text: 'La imagen '+ file.name+' pesa '+ (file.size/size).toFixed(2) + 'MB',
                            })
                        }
                    }else{
                        console.log('Archivo no permitido');
                        $('#images').addClass('is-invalid');
                        $('#images').siblings('.invalid-feedback').html('<strong> Archivo no permitido </strong>');
                    }
                }else{
                    Swal.fire({
                        type: 'error',
                        title: 'Fuera del límite de imágenes seleccionadas',
                        text: 'Recuerda que solo puedes seleccionar hasta 5 imágenes',
                    })
                }
            });
        }

    });

    $('#gallery-update').on('click', '#delete_report_image',function(){
        let imageIndex = $(this).data('index');
        let imagePosition = $(this).data('position');
        urlImagesReport.splice(imageIndex, 1);
        imagesRender.splice(imagePosition,1);
        previewImages(imagesRender);
    });
    $('#gallery-update').on('click', '#delete_input_image',function(){
        let imageIndex = $(this).data('index');
        let imagePosition = $(this).data('position');
        inputImages.splice(imageIndex, 1);
        imagesRender.splice(imagePosition,1);
        previewImages(imagesRender);
    });
});