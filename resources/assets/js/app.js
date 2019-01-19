
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
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';
import mixins from './mixins';

Vue.use(VueResource);
Vue.use(VuejsDialog);
Vue.use(VueInternationalization);

Vue.component('user-list', require('./components/UserList.vue'));
Vue.component('project-list', require('./components/ProjectList.vue'));
Vue.component('rehearsal-list', require('./components/RehearsalList.vue'));
Vue.component('semester-list', require('./components/SemesterList.vue'));
Vue.component('filters', require('./components/Filters.vue'));
Vue.component('picture-input', require('vue-picture-input'));
Vue.component('accept-decline', require('./components/AcceptDecline.vue'));
Vue.component('attendance', require('./components/Attendance.vue'));
Vue.component('avatar', require('./components/Avatar.vue'));
Vue.component('user-profile', require('./components/UserProfile.vue'));
Vue.mixin(mixins.global);

const lang = document.documentElement.lang.substr(0, 2); 

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

const app = new Vue({
    el: '#app',
    i18n,
    mixins
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

    $('body').on('input', '#firstname, #surname', function (event) {
        $('#username').val($('#firstname').val() + ' ' + $('#surname').val());
    });
});
