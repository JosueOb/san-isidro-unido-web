const Swal = require('sweetalert2')

import { locateMarker, setPosition, location } from './map';
import { phone_array, resetValues } from './phone_numbers';
import { newImages, resetNumberOfImagesAllowed, resetImages, oldImages } from './image-gallery';

var savedLocation = {};
var savedPhones = [];
// var currentLocation = location;
var newEventImages = newImages;
var oldEventImages = oldImages;
// var phone_numbers = phone_array;


async function updateMap() {
    //Se obtienen las coordenadas
    var infoLocation = document.querySelectorAll("#info span");
    infoLocation.forEach(element => {
        savedLocation[element.id] = element.textContent;
    });

    await setPosition(savedLocation);
    await locateMarker('map');
}

function updatePhones() {
    //Se obtienen los teléfono(s) registrados
    var infoPhones = document.querySelectorAll("#phone_group #phone_bagde");
    infoPhones.forEach(phone => {
        //Se eliminan los espacios en blanco
        savedPhones.push(phone.textContent.trim());
    });
    resetValues(savedPhones);
}

function updateImages() {
    resetNumberOfImagesAllowed(8);
    resetImages();
}

$(document).ready(function () {

    if ($('#map').length != 0 && $('#event-update').length != 0) {
        updateMap();
        updatePhones();
        updateImages();
    }

    $('#event-update').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);

        formData.append('ubication', JSON.stringify(location));
        
        formData.delete('new_images[]');
        newEventImages.forEach(function (image) {
            formData.append('new_images[]', image);
        });
        oldEventImages.forEach(function (image) {
            formData.append('old_images[]', image);
        });
        
        formData.delete('phone_numbers');
        phone_array.forEach(function (phone) {
            formData.append('phone_numbers[]', phone);
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
                    // console.log(data.success);
                    // $('#title').removeClass('is-invalid');
                    // $('#description').removeClass('is-invalid');
                    // $('#subcategory').removeClass('is-invalid');
                    // $('#start-time').removeClass('is-invalid');
                    // $('#end-time').removeClass('is-invalid');
                    // $('#start-date').removeClass('is-invalid');
                    // $('#end-date').removeClass('is-invalid');
                    // $('#responsible').removeClass('is-invalid');
                    // $('#phone_numbers').removeClass('is-invalid');
                    // $('#ubication-description').removeClass('is-invalid');
                    // $('#images').removeClass('is-invalid');
                    
                    // Swal.fire({
                    //     position: 'top-end',
                    //     type: 'success',
                    //     title: 'Evento actualizado',
                    //     showConfirmButton: false,
                    //     timer: 1500,
                    //     allowOutsideClick: true,
                    // })
                    // // Se deshabilita el botón enviar
                    // $('#send-data').prop("disabled", true);
                    // $('#send-data').removeClass("btn-primary");
                    // $('#send-data').addClass("btn-success");

                    console.log(data.redirect);
                    // funciona como una redirección HTTP
                    // setTimeout(function(){ 
                    //     window.location.replace(data.redirect);
                    // }, 1000);
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
                        if (validationErrors.hasOwnProperty('id')) {
                            $('#subcategory').addClass('is-invalid');
                            $('#subcategory').siblings('.invalid-feedback').html('<strong>' + validationErrors['id'][0] + '</strong>');
                        } else {
                            $('#subcategory').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('start-time')) {
                            $('#start-time').addClass('is-invalid');
                            $('#start-time').siblings('.invalid-feedback').html('<strong>' + validationErrors['start-time'][0] + '</strong>');
                        } else {
                            $('#start-time').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('end-time')) {
                            $('#end-time').addClass('is-invalid');
                            $('#end-time').siblings('.invalid-feedback').html('<strong>' + validationErrors['end-time'][0] + '</strong>');
                        } else {
                            $('#end-time').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('start-date')) {
                            $('#start-date').addClass('is-invalid');
                            $('#start-date').siblings('.invalid-feedback').html('<strong>' + validationErrors['start-date'][0] + '</strong>');
                        } else {
                            $('#start-date').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('end-date')) {
                            $('#end-date').addClass('is-invalid');
                            $('#end-date').siblings('.invalid-feedback').html('<strong>' + validationErrors['end-date'][0] + '</strong>');
                        } else {
                            $('#end-date').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('responsible')) {
                            $('#responsible').addClass('is-invalid');
                            $('#responsible').siblings('.invalid-feedback').html('<strong>' + validationErrors['responsible'][0] + '</strong>');
                        } else {
                            $('#responsible').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('phone_numbers')) {
                            $('#phone_numbers').addClass('is-invalid');
                            $('#phone_numbers').siblings('.invalid-feedback').html('<strong>' + validationErrors['phone_numbers'][0] + '</strong>');
                        } else {
                            if (validationErrors.hasOwnProperty('phone_numbers.0')) {
                                $('#phone_numbers').addClass('is-phone_numbers');
                                $('#phone_numbers').siblings('.invalid-feedback').html('<strong>' + validationErrors['phone_numbers.0'][0] + '</strong>');
                            } else {
                                $('#phone_numbers').removeClass('is-invalid');
                            }
                        }
                        if (validationErrors.hasOwnProperty('ubication-description')) {
                            $('#ubication-description').addClass('is-invalid');
                            $('#ubication-description').siblings('.invalid-feedback').html('<strong>' + validationErrors['ubication-description'][0] + '</strong>');
                        } else {
                            $('#ubication-description').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('ubication')) {
                            Swal.fire({
                                position: 'top-end',
                                type: 'error',
                                title: 'Ubicación',
                                text: 'Debe haber seleccionado una ubicación en el mapa',
                            })
                        }
                        if (validationErrors.hasOwnProperty('new_images')) {
                            $('#images').addClass('is-invalid');
                            $('#images').siblings('.invalid-feedback').html('<strong>' + validationErrors['new_images'][0] + '</strong>');
                        } else {
                            if (validationErrors.hasOwnProperty('images_allowed')) {
                                $('#images').addClass('is-invalid');
                                $('#images').siblings('.invalid-feedback').html('<strong>' + validationErrors['images_allowed'][0] + '</strong>');
                            } else {
                                let thereIsValidation = false;
                                let value = 0;
                                for (let index = 0; index < newEventImages.length; index++) {
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
                    }
                }
            }
        });
    });
});
