const flatpickr = require("flatpickr");
require("flatpickr/dist/themes/light.css");
let weekdays = {
    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],         
};
let months = {
    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
    longhand: ['Enero', 'Febrero', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
};

var inputStartDate = flatpickr('#start-date', {
    locale: {
        firstDayOfWeek: 1,
        weekdays: weekdays , 
        months: months,
    },
    minDate: "today",
    defaultDate: "today",
    dateFormat: "Y-m-d",
    allowInput:true,
    altInput: true,
    inline: false,
    disableMobile:true,
});
var inputEndtDate = flatpickr('#end-date', {
    locale: {
        firstDayOfWeek: 1,
        weekdays: weekdays , 
        months: months,
    },
    minDate: "today",
    dateFormat: "Y-m-d",
    allowInput:true,
    altInput: true,
    inline: false,
    disableMobile:true,
});


var inputStartTime = flatpickr('#start-time', {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    disableMobile:true,
});
var inputEndTime = flatpickr('#end-time', {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    disableMobile:true,
});


$('#start-time').removeAttr('readonly');
$('#end-time').removeAttr('readonly');