<script>
import {clientFetch} from "../helpers/index.js";
import Swal from "sweetalert2";
import router from "../router/index.js";

export default {
    data() {
        return {
            loading: false,
            error: null,
            nameHotel: '',
            nit: '',
            address: '',
            country: '',
            state: '',
            city: '',
            phone: ''
        }
    },
    methods: {
        handleSubmit() {

            this.error = '';

            if (!/^\d+-\d$/.test(this.nit)){
                this.error = 'El NIT no es válido';
                return;
            }

            const payload = {
                name: this.nameHotel,
                nit: this.nit,
                address: this.address,
                country: this.country,
                state: this.state,
                city: this.city,
                phone: this.phone || null
            }

            Swal.fire({
                title: `Registrando el hotel: ${this.nameHotel}`,
                didOpen: () => Swal.showLoading()
            });
            clientFetch('POST', payload, 'hotels')
                .then(() => {
                    router.push({name: 'NewBooking'});
                    Swal.fire({
                        title: "Hotel registrado",
                        icon: "success",
                    });
                }).catch(err => {
                    Swal.close();
                    let errorMessages = '';
                    const dataError = err.response.data.data;
                    Object.keys(dataError).forEach(field => {
                        const errors = dataError[field];
                        errors.forEach(errorMessage => {
                            errorMessages += `${errorMessage}\n`;
                        });
                    });

                this.error = errorMessages;
            });
        }
    }
}
</script>

<template>
    <div class='flex justify-between'>
        <ul class="flex">
            <li class="mr-6">
                <router-link class="text-blue-500 hover:text-blue-800" to="/">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
                    </svg>
                </router-link>
            </li>
        </ul>
        <h1 class="font-black text-4xl">Registro de Hotel</h1>
        <div class='flex items-center gap-2 text-gray-400 hover:text-black'>
        </div>
    </div>
    <div class="mt-10 flex justify-center">
        <form @submit.prevent="handleSubmit">
            <div v-if="error" class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4" role="alert">
                {{ error }}
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del hotel</label>
                    <input type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="nameHotel" placeholder="Nombre Hotel" required>
                </div>
                <div>
                    <label for="nit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIT</label>
                    <input type="text" id="nit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="nit" required>
                </div>
                <div>
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
                    <input type="text" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="address" required>
                </div>
                <div>
                    <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">País</label>
                    <input type="text" id="country" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="country" required>
                </div>
                <div>
                    <label for="state" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estado/Departamento</label>
                    <input type="text" id="state" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="state" required>
                </div>
                <div>
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ciudad</label>
                    <input type="text" id="city" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="city" required>
                </div>
            </div>
            <div class="mb-6">
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono (opcional)</label>
                <input type="tel" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="123-45-678" v-model="phone">
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Registrar Hotel
            </button>
        </form>
    </div>
</template>
