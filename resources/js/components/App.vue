<script>
import { ref, toRefs, computed, watchEffect, reactive, onMounted, onUpdated} from 'vue';
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net';
DataTable.use(DataTablesLib);

    export default {
        name: "DataTableComponent",
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

            const { bookings, table } = toRefs(props);
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

            const buttonsAction = (rows) => {
                console.log(rows)
            }


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

            return { columns, reactiveBookings, buttonsAction, read, deleteTableIsEmpty };
        },
        mounted() {
            this.loadingApp()
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
                                select: true,
                                serverSide: true,
                                drawCallback: deleteTableIsEmpty,
                                rowCallback: buttonsAction,
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
                            </tr>
                            </thead>
                        </DataTable>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
