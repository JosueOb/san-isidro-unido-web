// commonjs
const flatpickr = require("flatpickr")
require("flatpickr/dist/themes/light.css");

import {getCurrentLocation, getAddress, locateMarker, setPosition, location} from './map';


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
        

        var fechaInicio = flatpickr('#inital-date', {
            // enableTime: true,
            // dateFormat: "Y-m-d H:i"
        });
        var fechaFinal = flatpickr('#end-date', {
            // enableTime: true,
            // dateFormat: "Y-m-d H:i"
        });

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
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        var horaInicio = flatpickr('#initial-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
        var horaFinal = flatpickr('#end-time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
        // $('#inital-date').prop('required', true);
        $('#inital-date').removeAttr('readonly');
        $('#end-date').removeAttr('readonly');
        // $('#inital-time').removeAttr('readonly');
        // $('#inital-time').removeAttr('readonly');
    }
    
    $('#event-create').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        formData.append('ubication', JSON.stringify(location));

        console.log('título', formData.get('title'));
        console.log('descripción', formData.get('description'));
        console.log('categoría', formData.get('subcategory'));
        console.log('responsable', formData.get('responsible'));
        console.log('hora-inicio', formData.get('initial-time'));
        console.log('hora-fin', formData.get('end-time'));
        console.log('fecha-inicio', formData.get('inital-date'));
        console.log('fecha-fin', formData.get('end-date'));
        console.log('fecha', formData.get('date'));
        console.log('descipción de ubicación', formData.get('ubication-description'));
        console.log('ubicación', formData.get('ubication'));

    });
    

});