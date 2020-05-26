const Swal = require('sweetalert2')

import{newImages} from './image-gallery';
import{newDocuments} from './document-gallery';

var images = newImages;
var documents = newDocuments;

$(document).ready(function () {

    $('#report-create').on('submit', function (event) {
         // Se evita el propago del submit
         event.preventDefault();

         //Se agrega el data del formData
         var formData = new FormData(this);
         formData.delete('new_images[]');
         formData.delete('new_documents[]');
 
         images.forEach(function (image) {
             formData.append('new_images[]', image);
         });

         documents.forEach(function (document) {
             formData.append('new_documents[]', document);
         });
 
        //  console.log(formData.getAll('new_images[]'));
        //  console.log(formData.getAll('new_documents[]'));

        $.ajax({
            type: 'POST',
            url: '../reports/store',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                
                if (data.success) {
                    // console.log(data);
                    $('#title').removeClass('is-invalid');
                    $('#description').removeClass('is-invalid');
                    $('#images').removeClass('is-invalid');
                    $('#documents').removeClass('is-invalid');
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: 'Informe publicado',
                        showConfirmButton: false,
                        timer: 1500,
                        allowOutsideClick: true,
                    })
                    // Se deshabilita el botón enviar
                    $('#send-data').prop("disabled", true);
                    $('#send-data').removeClass("btn-primary");
                    $('#send-data').addClass("btn-success");

                    // funciona como una redirección HTTP
                    setTimeout(function(){ 
                        window.location.replace('../reports');
                    }, 1000);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                var getErrors = jqXHR.responseJSON ? jqXHR.responseJSON : null;
                //
                if(getErrors){
                    //Se obtienen los error de validación por parte de Laravel
                    var validationErrors = getErrors.errors ? getErrors.errors : null;
                    
                    if (validationErrors) {
                        console.log(validationErrors);
                        if (validationErrors.hasOwnProperty('title')) {
                            $('#title').addClass('is-invalid');
                            $('#title').siblings('.invalid-feedback').html('<strong>' + validationErrors['title'][0] + '</strong>');
                        } else {
                            $('#title').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('description')) {
                            $('#description').addClass('is-invalid');
                            $('#description').siblings('.invalid-feedback').html('<strong>' + validationErrors['description'][0] + '</strong>');
                        } else {
                            $('#description').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('new_images')) {
                            $('#images').addClass('is-invalid');
                            $('#images').siblings('.invalid-feedback').html('<strong>' + validationErrors['new_images'][0] + '</strong>');
                        } else {
                            let thereIsValidation = false;
                            let value = 0;
                            for (let index = 0; index < images.length; index++) {
                                if (validationErrors.hasOwnProperty('new_images.'+index)) {
                                    thereIsValidation = true;
                                    value = index;
                                    break;
                                }
                            }
                            if (thereIsValidation) {
                                $('#images').addClass('is-invalid');
                                $('#images').siblings('.invalid-feedback').html('<strong>' + validationErrors['new_images.'+value][0] + '</strong>');
                            }else {
                                $('#images').removeClass('is-invalid');
                            }
                        }
                        if (validationErrors.hasOwnProperty('new_documents')) {
                            $('#documents').addClass('is-invalid');
                            $('#documents').siblings('.invalid-feedback').html('<strong>' + validationErrors['new_documents'][0] + '</strong>');
                        } else {
                            let thereIsValidation = false;
                            let value = 0;
                            for (let index = 0; index < documents.length; index++) {
                                if (validationErrors.hasOwnProperty('new_documents.'+index)) {
                                    thereIsValidation = true;
                                    value = index;
                                    break;
                                }
                            }
                                if (thereIsValidation) {
                                $('#documents').addClass('is-invalid');
                                $('#documents').siblings('.invalid-feedback').html('<strong>' + validationErrors['new_documents.'+value][0] + '</strong>');
                            }else {
                                $('#documents').removeClass('is-invalid');
                            }
                        }
                    }
                }
            }
        });
    });
});