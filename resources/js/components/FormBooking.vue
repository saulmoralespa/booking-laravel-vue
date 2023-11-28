<script>
import Swal from "sweetalert2";
import {clientFetch} from "../helpers/index.js";
import router from "../router/index.js";
export default {
    props: {
        mode: {
            type: String,
            required: true,
        },
        bookingData: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            formData: {
                name: this.bookingData.name || '',
                documentNumber: this.bookingData.documentNumber || '',
                numberPeople: this.bookingData.numberPeople || 0,
                numberNights: this.bookingData.numberNights || 0,
                arrivalDate: this.bookingData.arrivalDate || '',
                totalBooking: this.bookingData.totalBooking || 0,
                selectedStatus: this.bookingData.status || '',
                hotels: [],
                idBooking: null,
                hotelId: null,
                clientId: null
            },
            formatter: {
                date: 'YYYY-M-DD',
                month: 'MMM',
            }
        };
    },
    watch: {
        bookingData: {
            handler(newVal) {
                this.formData.name = newVal.name || '';
                this.formData.documentNumber = newVal.documentNumber || '';
                this.formData.numberPeople = newVal.numberPeople || '';
                this.formData.numberNights = newVal.numberNights || '';
                this.formData.arrivalDate = newVal.arrivalDate || '';
                this.formData.totalBooking = newVal.totalBooking || '';
                this.formData.selectedStatus = newVal.selectedStatus || '';
                this.formData.idBooking = newVal.idBooking;
                this.formData.clientId = newVal.clientId;
                this.formData.hotels = newVal.hotels;
            },
            deep: true,
        },
    },
    methods: {
        handleSubmit() {
            let payload = {
                name: this.formData.name,
                document_number: this.formData.documentNumber,
                arrival_date: this.formData.arrivalDate,
                number_people: this.formData.numberPeople,
                number_nights: this.formData.numberNights,
                total_amount: parseFloat(this.formData.totalBooking),
                status: this.formData.selectedStatus,
                hotel_id: this.formData.hotelId
            }

            Swal.fire({
                title: this.mode === 'edit' ? `Actualizando reservación ${this.formData.idBooking}` : `Creando nueva reservación para ${this.formData.name}`,
                didOpen: () => Swal.showLoading()
            });

            if (this.mode === 'edit'){
                payload = {...payload, id: this.formData.idBooking, client_id: this.formData.clientId}
                clientFetch('PUT', payload, `bookings/${this.formData.idBooking}`)
                    .then(() => {
                        router.push({name: 'Home'});
                        Swal.fire({
                            title: "Reservación actualizada",
                            icon: "success",
                    });
                });
            }else{
                clientFetch('POST', payload, 'bookings')
                    .then(() => {
                        router.push({name: 'Home'});
                        Swal.fire({
                            title: "Reservación creada",
                            icon: "success"
                        });
                });
            }
        },
    },
};
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
        <h1 class="font-black text-4xl">{{ mode === 'edit' ? 'Editar Reserva' : 'Crear Reserva' }}</h1>
        <div class='flex items-center gap-2 text-gray-400 hover:text-black'>
        </div>
    </div>
    <div class="mt-10 flex justify-center">
        <form @submit.prevent="handleSubmit">
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                    <input type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="formData.name" placeholder="John" required>
                </div>
                <div>
                    <label for="document_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de documento</label>
                    <input type="text" id="document_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="formData.documentNumber" required>
                </div>
                <div>
                    <label for="number_people" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de huéspedes</label>
                    <input type="number" id="number_people" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="formData.numberPeople" placeholder="" required>
                </div>
                <div>
                    <label for="number_nights" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad de noches</label>
                    <input type="number" id="number_nights" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="formData.numberNights" placeholder="" required>
                </div>
            </div>
            <div class="mb-6">
                <label for="number_people" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de llegada</label>
                <vue-tailwind-datepicker
                    v-model="formData.arrivalDate"
                    :formatter="formatter"
                    as-single
                />
            </div>
            <div class="mb-6">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estado</label>
                <select id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="formData.selectedStatus">
                    <option disabled value="">--Seleccione una opción--</option>
                    <option value="Provisional">Provisional</option>
                    <option value="Confirmada">Confirmada</option>
                    <option value="Cancelada">Cancelada</option>
                </select>
            </div>
            <div v-if="formData.hotels" class="mb-6">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hotel</label>
                <select id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="formData.hotelId">
                    <option disabled value="">--Seleccione una opción--</option>
                    <option v-for="hotel in formData.hotels" :value="hotel.id">{{ hotel.name }}</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                <input type="text" id="total" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="formData.totalBooking" required>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ mode === 'edit' ? 'Actualizar Reservación' : 'Crear Reservación' }}
            </button>
        </form>
    </div>
</template>
