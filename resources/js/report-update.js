const Swal = require('sweetalert2')
$(document).ready(function(){

    console.log('documento listo....');
    //Se realiza la lectura de las imagenes que que encuentren en la sección de gallería
    var getImagesReport = document.querySelectorAll("#gallery-update .gallery-item img");
    //Se realiza la lectura del documento adjuntado por el usuario
    var getDocumentReport = document.querySelectorAll("#gallery-document .gallery-item a");
    // console.log(getDocumentReport);

    // Images
    var oldImagesReport = [];
    var newImagesReport = [];
    var imagesRender = [];
    // Document
    var urlDocumentReport = [];
    var newDocument = [];

    getImagesReport.forEach(function(image, index){
        var imageRender = new Array();
        var images = new Array();

        imageRender['src'] = image.src;
        images['report'] = imageRender;

        imagesRender.push(images);
        oldImagesReport.push(image.dataset.image);
    });

    // console.log(imagesRender);

    getDocumentReport.forEach(function(file){
        urlDocumentReport.push(file.dataset.document);
    });
    
    // console.log(urlDocumentReport);
    
    const previewImages = images =>{
        let imageItem = '';
        var numReport = 0;
        var numInputImages = 0;
        
        images.forEach(function(image, indice){
            for (var group in image){
                if(group === 'report'){
                    image[group]['index'] = numReport++;
                }
                if(group === 'input'){
                    image[group]['index'] = numInputImages++;
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
    
    const previewDocument = file_array =>{
        
        let documentItem = '';
        file_array.forEach(function(file, indice){
            documentItem += `
            <div class='gallery-item'>
                <i class="fas fa-file-pdf image-document"></i>
                <p class="document-name">${file.name}</p>
                <i class="fas fa-trash-alt image-cancel" data-no="${indice}"></i>
            </div>
            `;
        });

        document.getElementById('gallery-document').innerHTML = documentItem;
    }

    previewImages(imagesRender);
    
    //Al seleccionar el input file
     $('#inputImages').on('change', function(event){
        $('#inputImages').removeClass('is-invalid');

        //Se obtiene las imagenes del input
        var files = event.target.files;
        let numberOfImagesAllowed = 5;
        var imagesLength = imagesRender.length;
        let size = 1048576;//equivale a 1MB
        
        
        //se verifica que se haya seleccionado alguna imágen
        if(files){
            //se recorre cada archivo para verificar que sea una imágen
            [].forEach.call(files, function(file, index){

                // if(imagesRender.length < numberOfImagesAllowed){
                    // console.log('Seleccionó una imagen');
                    // console.log(imagesRender.length);
                    //console.log("imagesLength"+imagesLength);

                    if ( /\.(jpe?g|png)$/i.test(file.name) ) {
                        //Si la imagen es menor a 1MB
                        if(file.size < size){
                            var reader = new FileReader();
                            reader.onload = (event) => {
                                if(imagesRender.length < numberOfImagesAllowed){
                                    newImagesReport.push(file);

                                    var imageRender = new Array();
                                    var images = new Array();
                                    
                                    imageRender['src'] = event.target.result; 
                                    images['input'] = imageRender;
    
                                    imagesRender.push(images); 
    
                                    previewImages(imagesRender);
                                    console.log(imagesRender.length)
                                    imagesLength = imagesRender.length;
                                }else{
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Fuera del límite de imágenes seleccionadas',
                                        text: 'Recuerda que solo puedes seleccionar hasta 5 imágenes',
                                    })
                                }
                            }
                            reader.readAsDataURL(files.item(index));
                            // console.log("imagesRender.length" +imagesRender.length);
                        }else{
                            Swal.fire({
                                type: 'error',
                                title: 'Fuera del límite de 1MB',
                                text: 'La imagen '+ file.name+' pesa '+ (file.size/size).toFixed(2) + 'MB',
                            })
                        }
                    }else{
                        console.log('Archivo no permitidos');
                        $('#inputImages').addClass('is-invalid');
                        $('#inputImages').siblings('.invalid-feedback').html('<strong> Archivo no permitido </strong>');
                    }
                // }else{
                //     Swal.fire({
                //         type: 'error',
                //         title: 'Fuera del límite de imágenes seleccionadas',
                //         text: 'Recuerda que solo puedes seleccionar hasta 5 imágenes',
                //     })
                // }
            });
        }
    });

    $('#imputDocument').on('change', function(event){
        $('#imputDocument').removeClass('is-invalid');
        //Se obtiene el nuevo documento seleccionado
        var newFile = event.target.files[0];
        let size = 5242880;//equivale a 5MB (bytes)
        // document_array.push(file);
        if(newFile){
            // console.log(document_array.length);
            //Se verifica que si ya se ha seleccionado un documento
            if(!newDocument.length && !urlDocumentReport.length){
                // console.log(newDocument);
                // console.log(urlDocumentReport);
                if( /\.(pdf)$/i.test(newFile.name)){
                    if(newFile.size < size){
                        console.log(newFile.name);
                        newDocument.push(newFile);
                        previewDocument(document_array)
                    }else{
                        Swal.fire({
                            type: 'error',
                            title: 'Fuera del límite de 5MB',
                            text: 'El documento '+ file.name+' pesa '+ (file.size/1048576).toFixed(2) + 'MB',
                        })
                    }
                }else{
                    console.log('El formato del documento no es permitido');
                    $('#document').addClass('is-invalid');
                    $('#document').siblings('.invalid-feedback').html('<strong> Archivo no permitido </strong>');
                }
            }else{
                Swal.fire({
                    type: 'error',
                    title: 'Fuera del límite',
                    text: 'Recuerda que solo puedes subir un documento PDF',
                })
            }
        }
    });


    $('#gallery-update').on('click', '#delete_report_image',function(){
        let imageIndex = $(this).data('index');
        let imagePosition = $(this).data('position');
        oldImagesReport.splice(imageIndex, 1);
        imagesRender.splice(imagePosition,1);
        previewImages(imagesRender);
    });
    $('#gallery-update').on('click', '#delete_input_image',function(){
        let imageIndex = $(this).data('index');
        let imagePosition = $(this).data('position');
        newImagesReport.splice(imageIndex, 1);
        imagesRender.splice(imagePosition,1);
        previewImages(imagesRender);
    });

    //AJAX
    $('#report-update').on('submit', function(event){

        // Se evita el propago del submit
        event.preventDefault();
        
         //Se agrega el data del formData
        var formData = new FormData(this);
        formData.delete('images[]');

        newImagesReport.forEach(function(image){
            formData.append('images[]', image);
        });
        oldImagesReport.forEach(function(image){
            formData.append('images_report[]', image);
        });
        console.log('Imagenes nuevas'+formData.getAll("images[]"));
        console.log('Imagenes antiguas'+formData.getAll("images_report[]"));

         $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success:function(data){
                console.log(data);
                

                if(data.success){
                    $('#title').removeClass('is-invalid');
                    $('#description').removeClass('is-invalid');
                    $('#images').removeClass('is-invalid');
                    Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    title: 'Informe actualizado',
                    showConfirmButton: false,
                    timer: 1500,
                    allowOutsideClick: false,
                  })
                  // Se deshabilita el botón enviar
                  $('#send-data').prop("disabled", true);
                  $('#send-data').removeClass("btn-primary");
                  $('#send-data').addClass("btn-danger");
                    // funciona como una redirección HTTP
                    setTimeout(function(){ 
                        window.location.replace('../');
                    }, 1000);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                var getErrors = jqXHR.responseJSON;
                // console.log(getErrors);

                // //Se obtienen los error de validación por parte de Laravel
                var validationErrors = getErrors.errors ? getErrors.errors : null;
                
                if(validationErrors){
                    if(validationErrors.hasOwnProperty('title')){
                        $('#title').addClass('is-invalid');
                        $('#title').siblings('.invalid-feedback').html('<strong>'+validationErrors['title'][0]+'</strong>');
                    }else{
                        $('#title').removeClass('is-invalid');
                    }
                    if(validationErrors.hasOwnProperty('description')){
                        $('#description').addClass('is-invalid');
                        $('#description').siblings('.invalid-feedback').html('<strong>'+validationErrors['description'][0]+'</strong>');
                    }else{
                        $('#description').removeClass('is-invalid');
                    }

                    if(validationErrors.hasOwnProperty('images') || validationErrors.hasOwnProperty('images.0')){
                        var message = validationErrors.hasOwnProperty('images') ? validationErrors['images'][0]: validationErrors['images.0'][0];
                        console.log(message);
                    }

                    if(validationErrors.hasOwnProperty('images')){
                        $('#inputImages').addClass('is-invalid');
                        $('#inputImages').siblings('.invalid-feedback').html('<strong>'+validationErrors['images'][0]+'</strong>');
                    }else{
                        if(validationErrors.hasOwnProperty('images.0')){
                            $('#inputImages').addClass('is-invalid');
                            $('#inputImages').siblings('.invalid-feedback').html('<strong>'+validationErrors['images.0'][0]+'</strong>');
                        }else{
                            $('#inputImages').removeClass('is-invalid');
                        }
                    }
                }
              
                console.log(jqXHR.responseText);
            }
        });
    });
});