<script>
import { ref, toRefs, computed, watchEffect, reactive, onMounted} from 'vue';
import {createRouter, createWebHashHistory} from 'vue-router';
import Swal from 'sweetalert2';
import {clientFetch} from "../helpers/clientFetch.js";
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net';
DataTable.use(DataTablesLib);

export default {
    name: "App",
    components: {DataTable},
    props: {
        bookings: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            loading: false
        };
    },
    methods: {
        loadingApp(){
            this.loading = true
        }
    },
    emits: ["update:bookings"],
    setup(props, { emit }) {

        const { bookings } = toRefs(props);
        const reactiveBookings = ref(bookings.value);


        const buttons = (data, type, row) => {
            const statusBooking = row.status;
            const textButtonChange = statusBooking === 'Provisional' ? 'Confirmar' : 'Provisional';
            const dataAttrChange = statusBooking === 'Provisional' ? 'booking-confirm' : 'booking-provisional';
            const cancelOption = statusBooking === 'Confirmada' || statusBooking === 'Cancelada'  ? '' : `<li>
                            <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" booking-cancel="${row.id}" onclick="dropDownAction(this)">Cancelar</a>
                        </li>`;

         return`<button id="apple-imac-27-dropdown-button" onclick="dropDownOpen(this)" data-dropdown-toggle="apple-imac-27-dropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div id="apple-imac-27-dropdown" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="apple-imac-27-dropdown-button">
                        <li>
                            <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" booking-edit="${row.id}" onclick="dropDownAction(this)">Editar</a>
                        </li>
                        ${cancelOption}
                        <li>
                            <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" ${dataAttrChange}="${row.id}" onclick="dropDownAction(this)">${textButtonChange}</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white" booking-view="${row.id}" onclick="dropDownAction(this)">Ver</a>
                    </div>
                </div>`;
        }

        const columns = [
            {"data": "id"},
            {"data": "name"},
            {"data": "document_number"},
            {"data": "arrival_date"},
            {"data": "departure_date"},
            {"data": "total_amount"},
            {"data": "number_nights"},
            {"data": "number_people"},
            {"data": "status"},
            {
                data: 'action',
                render: buttons,
            },
        ];

        const read =()  =>{
            const componentTable = this.$refs.table.dt;
            componentTable.ajax.url('api/bookings').load();
        };

        const deleteTableIsEmpty = ({_iRecordsDisplay}) => {
            if (!_iRecordsDisplay){
                reactiveBookings.value = [];
            }
        };

        /*const computedBookings = computed({
            get: () => reactiveBookings.value,
            set: (data) => {
                reactiveBookings.value = data;
                emit('update:bookings', data);
            },
        });

        const computedLoading = computed({
            get: () => reactiveLoading.value,
            set: (data) => {
                reactiveLoading.value = data;
                emit('update:loading', true);
            },
        });

        watchEffect(() => {
            reactiveLoading.value = true;
        })*/;

        onMounted(async () => {
            const res = await fetch('api/bookings')
                .then(data => data.json());
            const { data } = res;
            reactiveBookings.value = data;
        });

        return {columns, reactiveBookings, read, deleteTableIsEmpty };
    },
    mounted() {
        this.loadingApp()
    }
}

let lastDropDownOpen;

window.dropDownOpen = el => {
    const containerButtons = el.nextElementSibling;
    const hiddeClass = 'hidden';
    if (lastDropDownOpen){
        lastDropDownOpen.classList.add(hiddeClass);
    }
    if (containerButtons.classList.contains(hiddeClass)){
        containerButtons.classList.remove(hiddeClass);
    }else{
        containerButtons.classList.add(hiddeClass);
    }

    lastDropDownOpen = containerButtons;
}

window.dropDownAction = el => {

    let idBooking = el.getAttribute('booking-cancel');

    let payload = {
        action: "cancel"
    }

    if (el.getAttribute('booking-edit')){
        idBooking = el.getAttribute('booking-edit');
    }else if (el.getAttribute('booking-provisional')){
        idBooking = el.getAttribute('booking-provisional');
        payload = {
            action: "provisional"
        }
        Swal.fire({
            title: "¿ Esta seguro de actualizar la reservación a provisional ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "No, actualizar",
            confirmButtonText: "Si, actualizar"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Actualizando reserva a provisional",
                    didOpen: () => Swal.showLoading()
                });
                clientFetch('PUT', idBooking, payload)
                    .then(() => {
                        Swal.fire({
                            title: "Reserva actualizada",
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                });
            }
        });
    }else if (el.getAttribute('booking-confirm')){
        idBooking = el.getAttribute('booking-confirm');
        payload = {
            action: "confirm"
        }
        Swal.fire({
            title: "¿ Esta seguro de confirmar la reservación ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "No, confirmar",
            confirmButtonText: "Si, confirmar"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Confirmando reserva",
                    didOpen: () => Swal.showLoading()
                });
                clientFetch('PUT', idBooking, payload)
                    .then(() => {
                        Swal.fire({
                            title: "Reserva canfirmada",
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                });

            }
        });
    }else if (el.getAttribute('booking-view')){
        idBooking = el.getAttribute('booking-view');
        Swal.fire({
            title: `Cargando detalles de la reserva: ${idBooking}`,
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });
        clientFetch('GET', idBooking).then(data => {
            const { arrival_date,  departure_date, number_nights, number_people, status, total_amount, name, document_number} = data;
            Swal.fire({
                title: `Reservación #${idBooking}`,
                html: `
                    <div>
                    <p><strong>Datos del huésped principal:</strong></p>
                    <p>Nombre: ${name}</p>
                    <p>N° documento: ${document_number}</p>
                    </div>
                    <p>Llegada: ${arrival_date}</p>
                    <p>Salida: ${departure_date}</p>
                    <p>Cantidad de noches: ${number_nights}</p>
                    <p>Número de huéspedes: ${number_people}</p>
                    <p>Estado de reservación: ${status}</p>
                    <p>Total reservación <strong>$${total_amount}</strong></p>
                  `,
                confirmButtonText: `Cerrar reservación`,
            });
        })

    }else{
        Swal.fire({
            title: "¿ Esta seguro de cancelar la reservación ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "No, cancelar",
            confirmButtonText: "Si, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Cancelando reserva",
                    didOpen: () => Swal.showLoading()
                });
                clientFetch('PUT', idBooking, payload)
                    .then(() => {
                    Swal.fire({
                        title: "Reserva cancelada",
                        icon: "success",
                    }).then(() => {
                        window.location.reload();
                    });
                });
            }
        });
    }
}

</script>

<template>
    <div class="container mx-auto">
        <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Administrar Reservas</h1>
        <section class="bg-gray-50 dark:bg-gray-900 py-3 sm:py-5">
            <div class="px-4 mx-auto max-w-screen-2xl lg:px-12">
                <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <DataTable
                            :data="reactiveBookings"
                            :columns="columns"
                            ref="table"
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                            :ajax="{
                                url: 'api/bookings'
                            }"
                            :options="{
                                responsive: true,
                                dom: 'Bfrtip',
                                select: true,
                                serverSide: true,
                                drawCallback: deleteTableIsEmpty,
                                language: {
                                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-CO.json'
                                }
                            }"
                            v-if="reactiveBookings?.length"
                        >
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Nombre cliente</th>
                                <th scope="col" class="px-4 py-3">N° documento</th>
                                <th scope="col" class="px-4 py-3">Fecha llegada</th>
                                <th scope="col" class="px-4 py-3">Fecha salida</th>
                                <th scope="col" class="px-4 py-3">Valor</th>
                                <th scope="col" class="px-4 py-3">N° noches</th>
                                <th scope="col" class="px-4 py-3">N° huespedes</th>
                                <th scope="col" class="px-4 py-3">Estado</th>
                                <th scope="col" class="px-4 py-3">Acción</th>
                            </tr>
                            </thead>
                        </DataTable>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
