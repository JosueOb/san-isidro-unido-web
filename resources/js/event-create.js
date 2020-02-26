// commonjs
const flatpickr = require("flatpickr")
require("flatpickr/dist/themes/light.css");

import {getCurrentLocation, getAddress, locateMarker, setPosition, location} from './map';
import{phone_array} from './phone_numbers';
import{newImagesReport, resetNumberOfImagesAllowed} from './image-gallery';

var phone_numbers = phone_array;
var images = newImagesReport;

var currentLocation = location;

async function loadMap(){
    var geolocationPosition = await getCurrentLocation()
                                    .then(coordinates => coordinates)
                                    .catch(errs =>{
                                        console.log('geolocationPosition', errs);
                                    });
    currentLocation = {
        'lat': geolocationPosition ? geolocationPosition.coords.latitude: null, 
        lng: geolocationPosition ? geolocationPosition.coords.longitude : null,
    };
    var address = await getAddress(currentLocation);
    currentLocation.address = address ? address : null;

    if(currentLocation.lat && currentLocation.lng && currentLocation.address ){
        setPosition(currentLocation);
    }
    
    locateMarker('map');
}

$(document).ready(function () {

    if($('#map').length != 0 && $('#event-create').length != 0){
        loadMap();
        resetNumberOfImagesAllowed(3);

        var fecha = flatpickr('#date', {
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],         
                }, 
                months: {
                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                    longhand: ['Enero', 'Febrero', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                },
            },
            mode: "range",
            minDate: "today",
            // defaultDate: "today",
            dateFormat: "Y-m-d",
            allowInput:true,
            inline: false
        });
        var horaInicio = flatpickr('#start-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            // allowInput:true,
        });
        var horaFinal = flatpickr('#end-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });

        $('#start-time').removeAttr('readonly');
        // $('#date').removeAttr('readonly');

    }

    
    $('#event-create').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        
        formData.append('ubication', JSON.stringify(location));
        
        formData.delete('images[]');
        images.forEach(function (image) {
            formData.append('images[]', image);
        });
        
        formData.delete('phone_numbers');
        phone_numbers.forEach(function (phone) {
            formData.append('phone_numbers[]', phone);
        });

        console.log('título', formData.get('title'));
        console.log('descripción', formData.get('description'));
        console.log('categoría', formData.get('subcategory'));
        console.log('responsable', formData.get('responsible'));
        console.log('hora-inicio', formData.get('start-time'));
        console.log('hora-fin', formData.get('end-time'));
        console.log('fecha', formData.get('date'));
        console.log('telefonos', formData.getAll('phone_numbers[]'));
        console.log('descipción de ubicación', formData.get('ubication-description'));
        console.log('ubicación', formData.get('ubication'));
        console.log('imagenes', formData.getAll('images[]') );

    });
    

});