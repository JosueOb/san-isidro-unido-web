const L = require('leaflet');

let location = {
    lat: -0.24320783421726888,
    lng: -78.49732162261353,
    address: "Casa barrial San Isidro de Puengasí, Quito, Pichincha, Ecuador"
};
//URL PARA OBTENER LA DIRECCION ENVIANDO COMO PARAMETROS LA (LAT, LNG) DE LAS COORDENADAS
const REVERSE_GEOCODING_ENDPOINT =
  "https://nominatim.openstreetmap.org/reverse";

//Obtiene la ubicación actual, utilizando HTML Geolocation API, se retorna una promesa
async function getCurrentLocation(){
    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };

    if(navigator.geolocation){
        // return await navigator.geolocation.getCurrentPosition(success, error, options);
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, options);
        });
    }else{
        console.warn('Geolocation is not supported by this browser.');
    }
}

//Se obtiene la dirección a partir de su positión long y lat
async function getAddress(location){
    const parameters = {
        format: "json",
        zoom: "18",
        addressdetails: "0",
        lat: location.lat,
        lon: location.lng,
    };
    try {
        const getAddress = await axios
        .get(REVERSE_GEOCODING_ENDPOINT, {
            params: parameters
        });
        return getAddress.data.display_name;
    } catch (error) {
        console.log('getAddress', error);
    }
}

//Se inicializa el mapa y se ubica un marcador en una determinada posición
function locateMarker(containerMap, showMarker = true){
    //Se permite o no el poder mover el marker
    var markerOptions = {};
    if(showMarker){
        markerOptions['draggable'] = "true"
    }
    //Opciones del mapa
    var mapOptions = {
        zoomControl: true,
        attributionControl: true,
        center: [
            location.lat,
            location.lng
        ],
        zoom: 17
    };
    //Se inicializa el mapa
    var map = L.map(containerMap, mapOptions);
    //Añadir la capa al mapa
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "Mapa",
        maxZoom: 18,
        updateWhenIdle: true,
        reuseTiles: true
    }).addTo(map);

    var marker = L.marker([
        location.lat,
        location.lng
    ], markerOptions).addTo(map);

    if(showMarker){
        //Se permite el poder mover el marcador
        marker.on('dragend', async e =>{
            const newLocation = await e.target.getLatLng();
            const newAddress = await getAddress(newLocation);
            location.lat = newLocation.lat;
            location.lng = newLocation.lng;
            location.address = newAddress ? newAddress : null;
            marker.bindPopup(location.address).openPopup();
        });
    }
    
    marker.bindPopup(location.address).openPopup();
}

//Se cambia la variable global location
function setPosition(newLocation){
    if(newLocation){
        location = newLocation; 
    }
}

export{ getCurrentLocation, getAddress, setPosition, locateMarker, location}