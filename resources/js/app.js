import './bootstrap';

import {createApp} from 'vue';
import VueTailwindDatepicker from 'vue-tailwind-datepicker';
import router from "./router/index.js";
import App from './layouts/App.vue';
createApp(App)
    .use(router)
    .use(VueTailwindDatepicker)
    .mount("#app");
