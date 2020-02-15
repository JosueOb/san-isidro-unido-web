const Swal = require('sweetalert2')

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
    }

});