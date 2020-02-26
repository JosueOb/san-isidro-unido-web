const flatpickr = require("flatpickr");
require("flatpickr/dist/themes/light.css");

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