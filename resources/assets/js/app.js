/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import {routes} from "./routes";
import VueResource from 'vue-resource';
import VuejsDialog from "vuejs-dialog"
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';
import mixins from './mixins';
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router';
import store from './vuex/store.js';
import VueFlashMessage from 'vue-flash-message';
import Datetime from 'vue-datetime';
import 'vue-datetime/dist/vue-datetime.css'
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css'
import VeeValidate from 'vee-validate';

window.Popper = require('popper.js').default;
require('./bootstrap');

global.moment = require('moment');
require('tempusdominus-bootstrap-4');

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(VueResource);
Vue.use(VuejsDialog);
Vue.use(VueInternationalization);
Vue.use(BootstrapVue);
Vue.use(require('vue-moment'));
Vue.use(VueFlashMessage, {
    messageOptions: {
        timeout: 5000,
        important: true
    }
});
Vue.use(Datetime);
Vue.use(Multiselect);
Vue.use(VeeValidate);
Vue.component('multiselect', Multiselect);

Vue.mixin(mixins.global);

const lang = document.documentElement.lang.substr(0, 2); 

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    el: '#app',
    i18n,
    router,
    mixins,
    store,
    mounted () {
        if (this.$route.path !== '/login' && this.$route.path !== '/password/reset') {
            this.$store.dispatch('users/getCurrent');
        }
    }
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();

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
});
