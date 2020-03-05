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
        console.log('categoría', formData.get('id'));
        console.log('responsable', formData.get('responsible'));
        console.log('hora-inicio', formData.get('start-time'));
        console.log('hora-fin', formData.get('end-time'));
        console.log('fecha-inicio', formData.get('start-date'));
        console.log('fecha-final', formData.get('end-date'));
        console.log('telefonos', formData.getAll('phone_numbers[]'));
        console.log('descipción de ubicación', formData.get('ubication-description'));
        console.log('ubicación', formData.get('ubication'));
        console.log('imagenes', formData.getAll('images[]') );


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
                    console.log(data.form);
                    console.log(data.success);
                    console.log(data.validated);
                    // $('#title').removeClass('is-invalid');
                    // $('#description').removeClass('is-invalid');
                    // $('#images').removeClass('is-invalid');
                    // $('#document').removeClass('is-invalid');
                    // Swal.fire({
                    //     position: 'top-end',
                    //     type: 'success',
                    //     title: 'Informe publicado',
                    //     showConfirmButton: false,
                    //     timer: 1500,
                    //     allowOutsideClick: true,
                    // })
                    // Se deshabilita el botón enviar
                    // $('#send-data').prop("disabled", true);
                    // $('#send-data').removeClass("btn-primary");
                    // $('#send-data').addClass("btn-danger");

                    // funciona como una redirección HTTP
                    // setTimeout(function(){ 
                    //     window.location.replace('../reports');
                    // }, 1000);
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
                    }
                }
            }
        });

    });
    

});