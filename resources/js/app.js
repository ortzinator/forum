require('./bootstrap');

import Vue from 'vue';
import Dropdown from './components/Dropdown.vue';
import Flash from './components/Flash.vue';
import Thread from './components/Thread.vue';
import Paginator from './components/Paginator.vue';
import UserNotifications from './components/UserNotifications.vue';

window.Vue = Vue;
window.Vue.prototype.authorize = function(handler) {
    let user = window.App.user;
    return user ? handler(user) : false;
};

window.events = new Vue();

window.flash = function(message, level = 'success') {
    window.events.$emit('flash', { message, level });
};

Vue.component('dropdown', Dropdown);
Vue.component('flash', Flash);
Vue.component('paginator', Paginator);
Vue.component('user-notifications', UserNotifications);

Vue.component('thread-view', Thread);

new Vue({
    el: '#app'
});
