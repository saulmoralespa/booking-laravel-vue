<script>

import { ref, toRefs, onMounted} from 'vue';
import {buttonsDrodown, clientFetch, modalContent} from "../helpers";
import router from "../router/index.js";
import Swal from 'sweetalert2';
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net';
DataTable.use(DataTablesLib);

export default {
    name: "DataTableBooking",
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
    setup(props) {

        const { bookings } = toRefs(props);
        const reactiveBookings = ref(bookings.value);

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
                render: buttonsDrodown,
            },
        ];

        const deleteTableIsEmpty = ({_iRecordsDisplay}) => {
            if (!_iRecordsDisplay){
                reactiveBookings.value = [];
            }
        };

        onMounted(async () => {
            const res = await clientFetch('GET');
            let { data } = res.data;
            if (!data.length) await router.push({name: 'HotelRegister'});

            reactiveBookings.value = data;
        });

        return {columns, reactiveBookings, deleteTableIsEmpty };
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

    if (el.getAttribute('booking-provisional')){
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
                clientFetch('PUT', payload, `bookings/${idBooking}`)
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
                clientFetch('PUT', payload, `bookings/${idBooking}`)
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
        clientFetch('GET', null, `bookings/${idBooking}`).then(data => {
            Swal.fire({
                title: `Reservación #${idBooking}`,
                html: modalContent(data.data),
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
                clientFetch('PUT', payload, `bookings/${idBooking}`)
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
    <div class="container mx-auto" v-if="reactiveBookings?.length">
        <div class='flex justify-between'>
            <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Administrar Reservas</h1>
            <router-link class="text-blue-500 hover:text-blue-800" to="/new-booking">
                Crear Nueva Reservación
            </router-link>
        </div>
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
