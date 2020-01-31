
//Se almacenan los los  números telefónicos válidos
let phone_array = [];
//Se establece la cantidad de teléfonos permitidos
var numberOfPhonesAllowed = 3;

//Se encarga de mostrar en el navegador los teléfonos ingresados
const print_phones = (array) => {
    let print = '';
    console.log('ingreso');
    array.forEach(function (element, index) {
        print += `
            <div class="input-group-prepend" id='phone_group'>
                <span class="input-group-text" id='phone_bagde'> 
                 ${element}
                  <i class="fas fa-minus-circle" id='delete_phone' data-no="${index}"></i>
                </span>
              </div>
      `;
    });
    $('#phone_group').html(print);
};

//Se elimina un determinado teléfono acorde su atributo data-no (posición del teléfono en el array)
//para proceder a eliminarlo
$('#phone_group').on('click', '#delete_phone', function () {
    let phone_index = $(this).data('no');
    // console.log('eliminar ' + phone_index);
    phone_array.splice(phone_index, 1);
    //Se imprime los teléfonos
    print_phones(phone_array);
    //Se verifica algunas condiciones para ya sea deshabilitar o agregar el atributo required al input
    disabledAndRequiredAttribute(phone_array, '#phone_numbers' , numberOfPhonesAllowed);
});

//Evento que se ejecuta cada vez que se ingrese un valor por teclado en el input
$('#phone_numbers').keyup(function () {
    //Se obtiene el valor ingresado
    var phone = $(this).val();
    //Se establece la regla de validación
    var pattern_phone = new RegExp('(^(09)[0-9]{8})+$|(^(02)[0-9]{7})+$');

    //Se verifica la cantidad de telefonos ingresados
    if (phone_array.length < numberOfPhonesAllowed) {
        //Se verifica que la cadena cumpla con la validación
        if (pattern_phone.test(phone)) {
            // console.log('Teléfono válido');
            phone_array.push(phone);
            print_phones(phone_array);
            $(this).val('');
            disabledAndRequiredAttribute(phone_array, this , numberOfPhonesAllowed);
        } else {
            console.log('Teléfono inválido');
        }
    }
});

//Función que permite verificar dos condiciones
//1.- Si el arreglo contiene algún elemento, se le remueve el atributo de requerido al input; caso contrario
// se lo agrega. Esto se realiza para verificar que se haya ingresado un teléfono y no obligar el ingreso de varios teléfonos
//2.- Si la cantidad de elementos del arreglo supera al numéro de teléfons permitidos, se le agrega el atributo disable, evitando
//el ingreso de teléfonos permitidos; caso contrario se lo remueve
const disabledAndRequiredAttribute = (array, idInput, amountAllowed) => {
    if(array.length){
        $(idInput).removeAttr('required');
    }else{
        $(idInput).prop('required', true);
    }

    if(array.length < amountAllowed){
        $(idInput).removeAttr('disabled');
    }else{
        $(idInput).prop('disabled', true);
    }
}

//Función que permite agregar un nuevo valor al arreglo phone_array, esto se realiza cuando se edite un
// registro que cuente con telefonos, para que todo el script trabaje con los valores recibidos y no desde cero
function resetValues(array){
    if(array.length){
        phone_array = array;
        print_phones(phone_array);
        disabledAndRequiredAttribute(phone_array, '#phone_numbers' , numberOfPhonesAllowed);
    }
    console.log(phone_array);
}

//Se exporta la función resertValues y el arreglo phone_array
export{resetValues, phone_array}