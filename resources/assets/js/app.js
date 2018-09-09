
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

import VueResource from 'vue-resource';
import VuejsDialog from "vuejs-dialog"

Vue.use(VueResource);
Vue.use(VuejsDialog)

Vue.component('user-list', require('./components/UserList.vue'));
Vue.component('filters', require('./components/Filters.vue'));
Vue.component('picture-input', require('vue-picture-input'));
Vue.component('accept-decline', require('./components/AcceptDecline.vue'));

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

    $('body').on('click', 'a.ajax', function (event) {
        event.preventDefault();

        var $this = $(this);

        $this.removeClass($this.data('unclicked-class'));
        $this.addClass($this.data('clicked-class'));
        $this.addClass('clicked');

        $this.parents('.btn-group').find('.btn').each(function () {
            $that = $(this);
            if (!$this.is($that)) {
                $that.removeClass($that.data('clicked-class'));
                $that.removeClass('clicked');
                $that.addClass($that.data('unclicked-class'));
            }
        });

        $.ajax({
            url: $this.attr('href')
        }).done(function (data) {

        });
    });

    $('body').on('click', 'input[type="checkbox"].check-all', function () {
        var $listTable = $(this).closest('.list-table');
        var $checkboxes = $listTable.find('[name="' + $(this).data('controls') + '"]');

        $checkboxes.prop('checked', $(this).prop('checked'));
        var $dropdown = $listTable.find('.dropdown');

        $dropdown.toggleClass('active');

        updateActionLinks();
    });

    var toggleListCheckbox = function () {
        var $listTable = $(this).closest('.list-table');
        var $checkboxes = $listTable.find('input[name="users[]"]');
        var checked = false;

        $checkboxes.each(function () {
            if ($(this).prop('checked')) {
                checked = true;
            }
        });

        var $dropdown = $listTable.find('.dropdown');

        if (checked) {
            $dropdown.addClass('active');
        } else {
            $dropdown.removeClass('active');
            $('input[type="checkbox"].check-all').prop('checked', false);
        }

        updateActionLinks();
    };

    var updateActionLinks = function () {
        var $actionLinks = $('.list-actions .dropdown-menu a');

        $actionLinks.each(function () {
            var href = $(this).data('href');
            href += '?' + $('input[name="users[]"]').serialize();

            $(this).attr('href', href);
        });
    };

    $('body').on('click', 'input[name="users[]"]', toggleListCheckbox);

    $('body').on('click', '.list-table li.row', function (event) {
        if ($(event.target).is('div')) {
            var $checkbox = $(this).find('input[type="checkbox"]');
            $checkbox.prop('checked', !$checkbox.prop('checked'));
            toggleListCheckbox.bind($checkbox)();
        }
    });

    $('body').on('input', '#firstname, #surname', function (event) {
        $('#username').val($('#firstname').val() + $('#surname').val());
    });
});
