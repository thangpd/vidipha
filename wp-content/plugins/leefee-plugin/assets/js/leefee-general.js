import alert_module from './modules/alert-module.js'


$(function () {
    $('body').on('click', function () {
        alert_module()
    })
})