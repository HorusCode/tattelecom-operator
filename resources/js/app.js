
require('./bootstrap');


import Vue from 'vue'
import Buefy from 'buefy'

Vue.use(Buefy);
Vue.config.debug = true;
Vue.config.devtools = true;

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));



new Vue({
    el: '#app',
});
