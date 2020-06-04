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

let configDate = {
    locale: {
        firstDayOfWeek: 1,
        weekdays: weekdays,
        months: months,
    },
    minDate: "today",
    dateFormat: "Y-m-d",
    allowInput: true,
    altInput: true,
    inline: false,
    disableMobile: true,
};
let configTime = {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    disableMobile: true,
    // allowInput: true,
    // altInput: true,
};

var inputStartDate = flatpickr('#start-date', configDate);
var inputEndtDate = flatpickr('#end-date', configDate);

var inputStartTime = flatpickr('#start-time', configTime);
var inputEndTime = flatpickr('#end-time', configTime);

var inputOpenTime = flatpickr('#open-time', configTime);
var inputCloseTime = flatpickr('#close-time', configTime);

// async function getCurrentDate() {
//     let today = new Date();
//     let day = today.getDate();
//     let month = today.getMonth() + 1;
//     let year = today.getFullYear();

//     if (day < 10) {
//         day = '0' + day;
//     }
//     if (month < 10) {
//         month = '0' + month;
//     }

//     today = year+'-'+month+'-'+day;

//     $('#start-date').val(today);
// }


$('#start-time').removeAttr('readonly');
$('#end-time').removeAttr('readonly');

$('#open-time').removeAttr('readonly');
$('#close-time').removeAttr('readonly');

// export{getCurrentDate}