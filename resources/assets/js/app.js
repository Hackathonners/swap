
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import BootstrapVue from 'bootstrap-vue';
import Vue from 'vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import 'bootstrap-vue/dist/bootstrap-vue.css';
import CsrfField from './components/CsrfField';
import ConfirmExchangeModal from './components/modals/ConfirmExchangeModal';
import DeclineExchangeModal from './components/modals/DeclineExchangeModal';
import DeleteExchangeModal from './components/modals/DeleteExchangeModal';
import ConfirmExchange from './directives/ConfirmExchange';
import DeclineExchange from './directives/DeclineExchange';
import DeleteExchange from './directives/DeleteExchange';

// Setup VueJS components and directives
export const eventBus = new Vue({});
Vue.use(BootstrapVue);
Vue.component('csrfField', CsrfField);
Vue.component('confirm-exchange-modal', ConfirmExchangeModal);
Vue.component('decline-exchange-modal', DeclineExchangeModal);
Vue.component('delete-exchange-modal', DeleteExchangeModal);
Vue.directive('confirm-exchange', ConfirmExchange);
Vue.directive('decline-exchange', DeclineExchange);
Vue.directive('delete-exchange', DeleteExchange);

const app = new Vue({
    el: '#app'
});

require('./file-input');
