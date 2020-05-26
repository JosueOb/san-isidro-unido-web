const Swal = require('sweetalert2')

import { newImages, resetImages, oldImages } from './image-gallery';
import { newDocuments, resetDocuments, oldDocuments } from './document-gallery';

var newReportImages = newImages;
var oldReportImages = oldImages;

var newReportDocuments = newDocuments;
var oldReportDocuments = oldDocuments;

$(document).ready(function () {

    if ($('#report-update').length != 0) {
        resetImages();
        resetDocuments();
    }

    $('#report-update').on('submit', function (event) {
        // Se evita el propago del submit
        event.preventDefault();

        //Se agrega el data del formData
        var formData = new FormData(this);

        formData.delete('new_images[]');
        newReportImages.forEach(function (image) {
            formData.append('new_images[]', image);
        });
        oldReportImages.forEach(function (image) {
            formData.append('old_images[]', image);
        });

        formData.delete('new_documents[]');
        newReportDocuments.forEach(function (document) {
            formData.append('new_documents[]', document);
        });
        oldReportDocuments.forEach(function (document) {
            formData.append('old_documents[]', document);
        });
        
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {

                if (data.success) {
                    console.log(data);
                    $('#title').removeClass('is-invalid');
                    $('#description').removeClass('is-invalid');
                    $('#images').removeClass('is-invalid');
                    $('#documents').removeClass('is-invalid');
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
                    $('#send-data').addClass("btn-success");
                    // funciona como una redirección HTTP
                    setTimeout(function () {
                        window.location.replace('../');
                    }, 1000);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                var getErrors = jqXHR.responseJSON ? jqXHR.responseJSON : null;
                // console.log(getErrors);
                if (getErrors) {
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
                            if (validationErrors.hasOwnProperty('images_allowed')) {
                                $('#images').addClass('is-invalid');
                                $('#images').siblings('.invalid-feedback').html('<strong>' + validationErrors['images_allowed'][0] + '</strong>');
                            }
                             else {
                                let thereIsValidation = false;
                                let value = 0;
                                for (let index = 0; index < newReportImages.length; index++) {
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
                        }
                        if (validationErrors.hasOwnProperty('new_documents')) {
                            $('#documents').addClass('is-invalid');
                            $('#documents').siblings('.invalid-feedback').html('<strong>' + validationErrors['new_documents'][0] + '</strong>');
                        } else {
                            if (validationErrors.hasOwnProperty('documents_allowed')) {
                                $('#documents').addClass('is-invalid');
                                $('#documents').siblings('.invalid-feedback').html('<strong>' + validationErrors['documents_allowed'][0] + '</strong>');
                            } else {
                                let thereIsValidation = false;
                                let value = 0;
                                for (let index = 0; index < newReportDocuments.length; index++) {
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
            }
        });
    });
});
