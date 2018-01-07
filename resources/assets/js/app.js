
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Popper = require('popper.js').default;
require('./bootstrap');

global.moment = require('moment');
require('tempusdominus-bootstrap-4');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('select[multiple]').multiselect();

    $('body').on('click', '.add-date', function (e) {
        e.preventDefault();
        $(this).prev('input[type="datetime-local"]').clone().insertBefore($(this));
    });

    $('body').on('click', '.add-voice', function (e) {
        e.preventDefault();
        $(this).prev('.row.voice').clone().insertBefore($(this));
    });

    $('body').on('click', '[data-toggle="modal"]', function () {
        var $loader = $('<div class="loader"></div>');
        var $modal = $($(this).data('target') + ' .modal-content');

        $modal.html($loader);
        $modal.load($(this).attr('href'), function () {
            $loader.remove();
            $('select[name="voices[]"]').trigger('change');
            $('select[multiple]').multiselect();
        });
    });

    $('body').on('click', '.modal-footer .btn-primary', function () {
        var $form = $('.modal-content form[method="POST"]');

        if ($form.length > 0) {
            $form.submit();
        }
    });

    $('body').on('click', '.active-filters li', function () {
        var $field = $('[name^="' + $(this).data('field') + '"]');

        if ($field.length > 0) {
            if ($field.is('input') || $field.is('select')) {
                $field.val('');
            }
        }

        $field.parents('.filter-form').submit();
    });

    $('body').on('change', '.edit-voices select[name="voices[]"]', function (event) {
        var $optionSelected = $("option:selected", this);
        var $numberField = $(this).closest('.form-group').next('.form-group').find('input[name="voiceNumbers[]"]');
        var $helpText = $(this).next('.help-text');

        if ($optionSelected.data('selected')) {
            $numberField.val($optionSelected.data('number'));
            $helpText.show();
        } else {
            $numberField.val('');
            $helpText.hide();
        }
    });
});
