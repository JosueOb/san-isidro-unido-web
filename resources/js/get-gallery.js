const Swal = require('sweetalert2')
$(document).ready(function(){

    console.log('documento listo....');
    //Se realiza la lectura de las imagenes que que encuentren en la sección de gallería
    var getImagesReport = document.querySelectorAll("#gallery-update .gallery-item img");
    var urlImagesReport = [];
    // var imagesReportRender = [];
    var inputImages = [];
    // var inputImagesRender = [];
    var totalImages = new Array();
    var imagesRender = [];


    getImagesReport.forEach(function(image, index){
        var imageRender = new Array();
        imageRender['index'] = index;
        imageRender['render'] = image.src;
        imageRender['group'] = 'report-image';
        imageRender['position'] = totalImages.length;
        // totalImages.push(imageRender);
        totalImages['report'] = imageRender;
        imagesRender.push(totalImages);
        // imagesReportRender.push(image.src);
        urlImagesReport.push(image.dataset.image);
        
    });
    console.log(imagesRender);

    const previewImages = images =>{
        let imageItem = '';
        // let counter = 0;

        images.forEach(function(image, indice){
            imageItem += `
            <div class="gallery-item">
                <div class="image-cancel" id=${image['group']} data-position="${image['position']}" data-index="${image['index']}">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <img src=${image['render']} alt='image_${image['index']}'>
            </div>
            `;
            // counter++;
        });
        var gallery = document.getElementById('gallery');
        if(gallery){
            gallery.innerHTML = imageItem
        }
        
        // var message = images.length > 0 ? 'Imágenes seleccionadas: '+ counter : 'Seleccione alguna imagen';
        // $('#images').siblings('.custom-file-label').addClass('selected').html(message);
    }

    previewImages(totalImages);
    
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
                // var numberOfImages = imagesReportRender.length + inputImages.length;
                if(totalImages.length < numberOfImagesAllowed){
                    console.log('Seleccionó una imagen');
                    if ( /\.(jpe?g|png)$/i.test(file.name) ) {
                        //Si la imagen es menor a 1MB
                        if(file.size < size){
                            inputImages.push(file);
                            var reader = new FileReader();
                            reader.onload = function(event){
                                var imageRender = new Array();
                                imageRender['index'] = inputImages.length - 1;
                                imageRender['render'] = event.target.result; 
                                imageRender['group'] = 'input-image';
                                imageRender['position'] = totalImages.length;
                                // totalImages.push(imageRender);
                                totalImages['input'] = imageRender;
                                // inputImagesRender.push(event.target.result);
                                // previewImages(inputImagesRender);
                                console.log(urlImagesReport);
                                console.log(inputImages);
                                console.log(totalImages);
                                previewImages(totalImages);
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

    $('.gallery').on('click', '#report-image',function(){
        let imageIndex = $(this).data('index');
        let imagePosition = $(this).data('position');
        //console.log(imageIndex);
        urlImagesReport.splice(imageIndex, 1);
        totalImages.splice(imagePosition,1);
        previewImages(totalImages);
        console.log(urlImagesReport);
        console.log(inputImages);
        console.log(totalImages);
    });
    $('.gallery').on('click', '#input-image',function(){
        let imageIndex = $(this).data('index');
        let imagePosition = $(this).data('position');
        //console.log(imageIndex);
        inputImages.splice(imageIndex, 1);
        totalImages.splice(imagePosition,1);
        previewImages(totalImages);
        console.log(urlImagesReport);
        console.log(inputImages);
        console.log(totalImages);
    });



});