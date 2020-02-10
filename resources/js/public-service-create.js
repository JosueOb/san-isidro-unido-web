const Swal = require('sweetalert2')
import {obtenerGeolocalizacion, 
    obtenerDireccion, añadirMarcadorAlMapa} from './map-create';

import{phone_array} from './phone_numbers';

var positionSelected = null;
var phone_numbers = phone_array;

async function cargarMapa() {
    if (navigator.geolocation) {
        try {
            //Esperar obtener la ubicacion actual
            let position = await obtenerGeolocalizacion();
            //Actualizar el punto actual
            positionSelected = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
                address: null
            };
            //Obtener la dirección actual
            const direccion = await obtenerDireccion(positionSelected);
            positionSelected.address = (direccion) ? direccion : null;
            añadirMarcadorAlMapa(positionSelected);
        } catch (err) {
            console.log("Error al obtener la localización", err);
        }
    }
}

$(document).ready(async function () {
    
    if($('#map').length != 0){
        await cargarMapa();
    }

    //AJAX
    $('#public-service-create').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        if(positionSelected){
            formData.append('ubication', JSON.stringify(positionSelected));
        }
        formData.delete('phone_numbers');
        phone_numbers.forEach(function (phone) {
            formData.append('phone_numbers[]', phone);
        });

        $.ajax({
            type: 'POST',
            url: '../public-service/store',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                
                if (data.success) {
                    //Se eliminan los mensajes de validación
                    $('#name').removeClass('is-invalid');
                    $('#description').removeClass('is-invalid');
                    $('#subcategory').removeClass('is-invalid');
                    $('#phone_numbers').removeClass('is-invalid');
                    $('#email').removeClass('is-invalid');
                    $('#ubication-description').removeClass('is-invalid');

                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: 'Servicio público registrado',
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
                        window.location.replace('../public-service');
                    }, 1000);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                var getErrors = jqXHR.responseJSON ? jqXHR.responseJSON : null;
                if(getErrors){
                    //Se obtienen los error de validación por parte de Laravel
                    var validationErrors = getErrors.errors ? getErrors.errors : null;
                    if (validationErrors) {
                        console.log(validationErrors);
                        if (validationErrors.hasOwnProperty('name')) {
                            $('#name').addClass('is-invalid');
                            $('#name').siblings('.invalid-feedback').html('<strong>' + validationErrors['name'][0] + '</strong>');
                        } else {
                            $('#name').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('description')) {
                            $('#description').addClass('is-invalid');
                            $('#description').siblings('.invalid-feedback').html('<strong>' + validationErrors['description'][0] + '</strong>');
                        } else {
                            $('#description').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('subcategory')) {
                            $('#subcategory').addClass('is-invalid');
                            $('#subcategory').siblings('.invalid-feedback').html('<strong>' + validationErrors['subcategory'][0] + '</strong>');
                        } else {
                            $('#subcategory').removeClass('is-invalid');
                        }
                        if (validationErrors.hasOwnProperty('email')) {
                            $('#email').addClass('is-invalid');
                            $('#email').siblings('.invalid-feedback').html('<strong>' + validationErrors['email'][0] + '</strong>');
                        } else {
                            $('#email').removeClass('is-invalid');
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
                    }
                }
            }
        });
    });
});