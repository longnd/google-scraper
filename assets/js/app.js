/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');
require('dropzone/dist/dropzone.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');

var Dropzone = require('dropzone');
Dropzone.autoDiscover = false;

$(document).ready(function() {
    $('.dropzone').dropzone({
        paramName: 'form[csvFile]',
        // addRemoveLinks: true,
        acceptedFiles: '.csv,text/csv,text/x-csv,application/csv,application/x-csv,text/comma-separated-values,text/x-comma-separated-values',
        maxFiles: 1,
        success: function() {
            $('#processingIndicator').show();
        }
    })
});
