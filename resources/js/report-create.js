// CommonJS
const Swal = require('sweetalert2')

$(document).ready(function(){

    var images = [];
    let renderImages = [];
    var document_array = [];

    const previewImages = images =>{
        let imageItem = '';

            images.forEach(function(image, indice){
                imageItem += `
                <div class="gallery-item">
                    <div class="image-cancel" data-no="${indice}"><i class="fas fa-trash-alt"></i></div>
                    <img src=${image} alt='image_${indice}'>
                </div>
                `;
            });
            
            document.getElementById('gallery-images').innerHTML = imageItem;
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

    //Al seleccionar el input file
    $('#images').on('change', function(event){
        $('#images').removeClass('is-invalid');
        //Se obtiene las imagenes del input
        var files = event.target.files;
        let numberOfSelectedImages = 0;
        let numberOfImagesAllowed = 5;
        let size = 1048576;//equivale a 1MB
        
        //se verifica que se haya seleccionado alguna imágen
        if(files){
            //se recorre cada archivo para verificar que sea una imágen
            [].forEach.call(files, function(file, index){
                if(numberOfSelectedImages < numberOfImagesAllowed){
                    console.log('Seleccionó una imagen');
                    
                    if ( /\.(jpe?g|png)$/i.test(file.name) ) {
                        //Si la imagen es menor a 1MB
                        if(file.size < size){
                            images.push(file);
                            var reader = new FileReader();
                            reader.onload = function(event){
                                renderImages.push(event.target.result);
                                previewImages(renderImages);
                                numberOfSelectedImages = renderImages.length;
                                console.log(numberOfSelectedImages);
                            }
                            reader.readAsDataURL(files.item(index));
                            // console.log(renderImages);
                        }else{
                            Swal.fire({
                                type: 'error',
                                title: 'Fuera del límite de 1MB',
                                text: 'La imagen '+ file.name+' pesa '+ (file.size/1048576).toFixed(2) + 'MB',
                            })
                        }
                        
                    }else{
                        console.log('Archivo no permitido');
                        $('#images').addClass('is-invalid');
                        $('#images').siblings('.invalid-feedback').html('<strong> Archivo/s no permitido/s </strong>');
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


    $('#gallery-images').on('click', '.image-cancel',function(){
        let imageIndex = $(this).data('no');
        //console.log(imageIndex);
        images.splice(imageIndex, 1);
        renderImages.splice(imageIndex,1);
        previewImages(renderImages);
    });

    $('#gallery-document').on('click', '.image-cancel',function(){
        let documentIndex = $(this).data('no');
        // console.log('eliminar'+ documentIndex);
        document_array.splice(documentIndex, 1);
        previewDocument(document_array);

    });

    $('#document').on('change', function(event){
        $('#document').removeClass('is-invalid');
        //Se obtiene el documento seleccionado
        var file = event.target.files[0];
        let size = 5242880;//equivale a 5MB (bytes)
        // document_array.push(file);
        if(file){
            // console.log(document_array.length);
            //Se verifica que si ya se ha seleccionado un documento
            if(!document_array.length){
                if( /\.(pdf)$/i.test(file.name)){
                    if(file.size < size){
                        console.log(file.name);
                        document_array.push(file);
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

    //AJAX
    $('#report-post').on('submit', function(event){
        // Se evita el propago del submit
        event.preventDefault();
        
         //Se agrega el data del formData
        var formData = new FormData(this);
        formData.delete('images[]');
        formData.delete('document');

         images.forEach(function(image){
            formData.append('images[]', image);
         });
         document_array.forEach(function(document){
            formData.append('document', document);
         });
        //  formData.append('document', document_array[0]);

         console.log(formData.getAll('images[]'));
         console.log(formData.getAll('document'));

         $.ajax({
            type:'POST',
            url: '../reports/store',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success:function(data){

                console.log(data);

                // if(data.success){
                //     $('#title').removeClass('is-invalid');
                //     $('#description').removeClass('is-invalid');
                //     $('#images').removeClass('is-invalid');
                //     Swal.fire({
                //     position: 'top-end',
                //     type: 'success',
                //     title: 'Informe publicado',
                //     showConfirmButton: false,
                //     timer: 1500,
                //     allowOutsideClick: false,
                //   })
                //     // funciona como una redirección HTTP
                //     setTimeout(function(){ 
                //         window.location.replace('../reports');
                //     }, 1000);
                // }
            },
            error: function(jqXHR, textStatus, errorThrown){
                var getErrors = jqXHR.responseJSON;

                //Se obtienen los error de validación por parte de Laravel
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
                    if(validationErrors.hasOwnProperty('images')){
                        $('#images').addClass('is-invalid');
                        $('#images').siblings('.invalid-feedback').html('<strong>'+validationErrors['images'][0]+'</strong>');
                    }else{
                        if(validationErrors.hasOwnProperty('images.0')){
                            $('#images').addClass('is-invalid');
                            $('#images').siblings('.invalid-feedback').html('<strong>'+validationErrors['images.0'][0]+'</strong>');
                        }else{
                            $('#images').removeClass('is-invalid');
                        }
                    }
                    if(validationErrors.hasOwnProperty('document')){
                        $('#document').addClass('is-invalid');
                        $('#document').siblings('.invalid-feedback').html('<strong>'+validationErrors['document'][0]+'</strong>');
                    }else{
                        $('#document').removeClass('is-invalid');
                    }
                }
              
                console.log(jqXHR.responseText);
            }
        });
    });
});