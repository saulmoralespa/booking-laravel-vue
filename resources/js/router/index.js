import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import HotelRegister from "../pages/HotelRegister.vue";
import EditBooking from '../pages/EditBooking.vue';
import NewBooking from "../pages/NewBooking.vue";

const routes = [
    {
        path: '/',
        component: Home,
        name: 'Home'
    },
    {
        path: '/hotel-register',
        component: HotelRegister,
        name: 'HotelRegister'
    },
    {
        path: '/new-booking',
        component: NewBooking,
        name: 'NewBooking'
    },
    {
        path: '/booking/:id/edit',
        component: EditBooking,
        name: 'EditBooking'
    },
]

const router = createRouter({
    routes,
    history: createWebHistory()
})

export default router;
