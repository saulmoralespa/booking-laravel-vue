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
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    <div class="w-full max-w-full">
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="table-responsive">
                <DataTable
                    :data="reactiveBookings"
                    :columns="columns"
                    ref="table"
                    :ajax="{
                      url: 'api/bookings'
                    }"
                    :options="{
                        select: true,
                        serverSide: true,
                        drawCallback: deleteTableIsEmpty,
                        rowCallback: buttonsAction
                    }"
                    v-if="reactiveBookings?.length"
                >
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre cliente</th>
                        <th>N° documento</th>
                        <th>Fecha llegada</th>
                        <th>Fecha salida</th>
                        <th>Valor</th>
                        <th>N° noches</th>
                        <th>N° huespedes</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
    </div>
</template>
