/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

import BootstrapVue from 'bootstrap-vue';
import Vue from 'vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import 'bootstrap-vue/dist/bootstrap-vue.css';
import CsrfField from './components/CsrfField';
import ConfirmExchangeModal from './components/modals/ConfirmExchangeModal';
import DeclineExchangeModal from './components/modals/DeclineExchangeModal';
import DeleteExchangeModal from './components/modals/DeleteExchangeModal';
import EnrollmentSelect from './components/EnrollmentSelect';
import ShiftSelect from "./components/ShiftSelect";
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
Vue.component('shift-select', ShiftSelect);
Vue.component('enrollment-select', EnrollmentSelect);
Vue.directive('confirm-exchange', ConfirmExchange);
Vue.directive('decline-exchange', DeclineExchange);
Vue.directive('delete-exchange', DeleteExchange);
import ElementUI from 'element-ui';
import CalendarEnrollments from './components/CalendarEnrollments.vue';
import CalendarExchanges from './components/CalendarExchanges.vue';
import FileInput from './components/FileInput.vue';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/en'

Vue.use(ElementUI, { locale });
Vue.component('calendar-enrollments', CalendarEnrollments);
Vue.component('calendar-exchanges', CalendarExchanges);
Vue.component('file-input', FileInput);

const app = new Vue({
  el: '#app',
});
