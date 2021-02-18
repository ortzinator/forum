require('./bootstrap');

import Vue from 'vue';
import Dropdown from './components/Dropdown.vue';
import Flash from './components/Flash.vue';
import Thread from './components/Thread.vue';
import Paginator from './components/Paginator.vue';
import UserNotifications from './components/UserNotifications.vue';
import AvatarForm from './components/AvatarForm.vue';
import Search from './components/Search.vue';

import InstantSearch from 'vue-instantsearch';

window.Vue = Vue;

let authorizations = require('./authorizations');

window.Vue.prototype.authorize = function(...params) {
    if (!window.App.signedIn) return false;
    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }
    return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;

window.events = new Vue();

window.flash = function(message, level = 'success') {
    window.events.$emit('flash', { message, level });
};

Vue.component('dropdown', Dropdown);
Vue.component('flash', Flash);
Vue.component('paginator', Paginator);
Vue.component('user-notifications', UserNotifications);
Vue.component('avatar-form', AvatarForm);
Vue.component('search', Search);

Vue.component('thread-view', Thread);

Vue.use(InstantSearch);

new Vue({
    el: '#app'
});
