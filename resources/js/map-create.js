const L = require('leaflet')

//URL PARA OBTENER LA DIRECCION ENVIANDO COMO PARAMETROS LA (LAT, LNG) DE LAS COORDENADAS
const REVERSE_GEOCODING_ENDPOINT =
  "https://nominatim.openstreetmap.org/reverse";
//VARIABLE PARA GUARDAR LA POSICION ACTUAL DEL USUARIO

//Opciones del Mapa
var mapOptions = {
  zoomControl: true,
  attributionControl: true,
  center: [-0.1376256, -78.46379519999999],
  zoom: 13
};

//Se verifica que exista el elemento mapa
if($('#map').length != 0){
  //Crear Mapa
  var map = L.map("map", mapOptions);
  //Añadir la capa al mapa
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "Hola",
    maxZoom: 18,
    updateWhenIdle: true,
    reuseTiles: true
  }).addTo(map);
}

// Función para obtener la dirección de unas coordenadas
async function obtenerDireccion(positionSelected) {
  //Llamar a la URL para obtener la direccion a partir de las coordenadas actuales
  const addressParams = {
    format: "json",
    zoom: "13",
    addressdetails: "0",
    lat:
      positionSelected && positionSelected.lat
        ? positionSelected.lat.toString()
        : "",
    lon:
      positionSelected && positionSelected.lng
        ? positionSelected.lng.toString()
        : ""
  };
  //Peticion HTTP para obtener la respuesta, puedes usar ajax, fetch
  //en este caso se utiliza axios
  try {
    const direccionResponse = await axios
      .get(REVERSE_GEOCODING_ENDPOINT, {
        params: addressParams
      });
    return direccionResponse.data.display_name;
  } catch (error) {
    console.log(error);
  }
}

// Añadir el marcador al Mapa
function añadirMarcadorAlMapa(punto) {
  //Crear el marcador
  const coordenadas = (punto) ? [punto.lat, punto.lng]: [-0.2368961059, -78.524460763];
  var marker = L.marker(coordenadas, {
    draggable: "true"
  }).addTo(map);
  //Añadir evento al terminar el drag del marcador para redibujar marcador y el HTML Posicion
  marker.on("dragend", async e => {
    const position = await e.target.getLatLng();
    const new_posicion = {lat: position.lat, lng: position.lng};
   
    const respuestaDireccion = await obtenerDireccion(new_posicion); //espero que se complete la funcion de obtener direccion para que se actualice el campo direccion del objeto posicion
    new_posicion.address = (respuestaDireccion) ? respuestaDireccion: null;
    mostrarPosicionEnHTML(new_posicion); //una vez completado la funcion, se muestra la posicion seleccionada
  });
  marker.bindPopup(`<b>Soy el Punto actual`).openPopup();
  mostrarPosicionEnHTML(punto);
}

//Mostrar en el HTML la longitud, latitud y direccion
function mostrarPosicionEnHTML(posicionSelected) {
  if (posicionSelected) {
    const texto = `
    <span>Latitud: ${posicionSelected.lat} 
    <br/> Longitud: ${posicionSelected.lng}</span>
    <br/> Dirección: ${
      posicionSelected.address ? posicionSelected.address : "Sin dirección"
      }</span>
    `;
    const mensaje = document.getElementById("ubicacion_seleccionada");
    mensaje.innerHTML = texto;
  }
}

// Retornar la funcion nativa de JS para obtener la posicion actual
function obtenerGeolocalizacion(options = {}) {
  return new Promise((resolve, reject) => {
    navigator.geolocation.getCurrentPosition(resolve, reject, options);
  });
}

export {obtenerDireccion, obtenerGeolocalizacion, añadirMarcadorAlMapa};