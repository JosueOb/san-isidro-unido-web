import {locateMarker, setPosition} from './map';

var showLocation = {};

async function showMap(){
    //Se obtienen las coordenadas
    var infoLocation = document.querySelectorAll("#info span");
    infoLocation.forEach(element => {
        showLocation[element.id] = element.textContent;
    });

    await setPosition(showLocation);
    await locateMarker('map', false);
}

$(document).ready( function () {

    if($('#map').length != 0 && $('#social-problem-show').length != 0){
        showMap();
    }
    
    if($('#map').length != 0 && $('#emergency-show').length != 0){
        showMap();
    }

});