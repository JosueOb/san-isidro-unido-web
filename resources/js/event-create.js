const Swal = require('sweetalert2')

import {getCurrentLocation, getAddress, locateMarker, setPosition, location} from './map';
import{phone_array} from './phone_numbers';
import{newImages, resetNumberOfImagesAllowed} from './image-gallery';
// import{getCurrentDate} from './time-date';

var phone_numbers = phone_array;
var images = newImages;

var currentLocation = location;

async function loadMap(){
    var geolocationPosition = await getCurrentLocation()
                                    .then(coordinates => coordinates)
                                    .catch(errs =>{
                                        console.log('geolocationPosition', errs);
                                    });
    currentLocation = {
        latitude: geolocationPosition ? geolocationPosition.coords.latitude: null, 
        longitude: geolocationPosition ? geolocationPosition.coords.longitude : null,
    };
    var address = await getAddress(currentLocation);
    currentLocation.address = address ? address : null;

    if(currentLocation.latitude && currentLocation.longitude && currentLocation.address ){
        setPosition(currentLocation);
    }
    
    locateMarker('map');
}

$(document).ready(function () {

    if($('#map').length != 0 && $('#event-create').length != 0){
        // getCurrentDate();
        loadMap();
        resetNumberOfImagesAllowed(3);
    }


    $('#event-create').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);

        formData.append('ubication', JSON.stringify(location));
        
        formData.delete('new_images[]');
        images.forEach(function (image) {
            formData.append('new_images[]', image);
        });
        
        formData.delete('phone_numbers');
        phone_numbers.forEach(function (phone) {
            formData.append('phone_numbers[]', phone);
        });

        // console.log('título', formData.get('title'));
        // console.log('descripción', formData.get('description'));
        // console.log('categoría', formData.get('id'));
        // console.log('responsable', formData.get('responsible'));
        // console.log('hora-inicio', formData.get('start-time'));
        // console.log('hora-fin', formData.get('end-time'));
        // console.log('fecha-inicio', formData.get('start-date'));
        // console.log('fecha-final', formData.get('end-date'));
        // console.log('telefonos', formData.getAll('phone_numbers[]'));
        // console.log('descipción de ubicación', formData.get('ubication-description'));
        // console.log('ubicación', formData.get('ubication'));
        // console.log('imagenes', formData.getAll('images[]') );


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
                    $('#title').removeClass('is-invalid');
                    $('#description').removeClass('is-invalid');
                    $('#id').removeClass('is-invalid');
                    $('#start-time').removeClass('is-invalid');
                    $('#end-time').removeClass('is-invalid');
                    $('#start-date').removeClass('is-invalid');
                    $('#end-date').removeClass('is-invalid');
                    $('#responsible').removeClass('is-invalid');
                    $('#phone_numbers').removeClass('is-invalid');
                    $('#ubication-description').removeClass('is-invalid');
                    $('#images').removeClass('is-invalid');
                    
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: 'Evento publicado',
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
                        window.location.replace('../events');
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
                        if (validationErrors.hasOwnProperty('images')) {
                            $('#images').addClass('is-invalid');
                            $('#images').siblings('.invalid-feedback').html('<strong>' + validationErrors['images'][0] + '</strong>');
                        } else {
                            if (validationErrors.hasOwnProperty('images.0')) {
                                $('#images').addClass('is-invalid');
                                $('#images').siblings('.invalid-feedback').html('<strong>' + validationErrors['images.0'][0] + '</strong>');
                            } else {
                                $('#images').removeClass('is-invalid');
                            }
                        }
                    }
                }
            }
        });
    });
});