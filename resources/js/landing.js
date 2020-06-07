try {
    require('bootstrap');
    window.$ = window.jQuery = require('jquery');

} catch (e) {
    console.log(e);
}