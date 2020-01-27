import {obtenerGeolocalizacion, 
    obtenerDireccion, añadirMarcadorAlMapa} from './map-create';

var positionSelected = null;
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
        
        console.log('name',formData.get('name'));
        console.log('description',formData.get('description'));
        console.log('category',formData.get('category'));
        console.log('phone_numbers',formData.get('phone_numbers'));
        console.log('ubication_description',formData.get('ubication-description'));
        console.log('PositionSelected',formData.get('ubication'));

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