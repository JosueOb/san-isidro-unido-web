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
        formData.append('ubication', JSON.stringify(positionSelected));
        formData.delete('phone_array');

        phone_numbers.forEach(function (phone) {
            formData.append('phone_array[]', phone);
        });

        $.ajax({
            type: 'POST',
            url: '../public-services/store',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        });
    });
});