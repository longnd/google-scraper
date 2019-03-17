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

/**
 * Periodically check if a scraping request is completed to redirect the user to the result page.
 * @param url
 */
function checkIfScrapingCompleted(url)
{
    $.ajax({
        url: url,
        success: function (response) {
            if (true === response.completed) {
                window.location.replace(response.report_url);
            }
        }
    });

    // re-run the status check if the status is still "incomplete"
    setTimeout(function() {
        checkIfScrapingCompleted(url)
    }, 5000);
}

$(document).ready(function() {
    $('.dropzone').dropzone({
        paramName: 'form[csvFile]',
        // addRemoveLinks: true,
        acceptedFiles: '.csv,text/csv,text/x-csv,application/csv,application/x-csv,text/comma-separated-values,text/x-comma-separated-values',
        maxFiles: 1,
        success: function(file, response) {
            $('#processingIndicator').show();

            let url = $('.dropzone').data('status-check-url').replace('place_holder', response.id);

            // call the status check for the first time, all subsequent calls will take care of themselves
            setTimeout(function() {
                checkIfScrapingCompleted(url)
            }, 5000);
        }
    })
});
