<script>
import FormBooking from "../components/FormBooking.vue";
import {clientFetch} from "../helpers/index.js";

export default {
    components: {
        FormBooking,
    },
    data() {
        return {
            mode: 'edit',
            bookingData: {},
        };
    },
    created() {
        this.$watch(
            () => this.$route.params,
            () => {
                this.fetchData()
            },
            { immediate: true }
        )
    },
    methods: {
        async fetchData() {
            await clientFetch('GET', null, `bookings/${this.$route.params.id}`).then(data => {
                const { id, client_id, arrival_date, number_people, number_nights, total_amount, status, name, document_number } = data.data;
                this.bookingData.idBooking = id;
                this.bookingData.clientId = client_id;
                this.bookingData.arrivalDate = arrival_date;
                this.bookingData.numberPeople = number_people;
                this.bookingData.numberNights = number_nights;
                this.bookingData.name = name;
                this.bookingData.documentNumber = document_number;
                this.bookingData.totalBooking = parseFloat(total_amount);
                this.bookingData.selectedStatus = status;
            });
        }
    }
};
</script>
<template>
    <form-booking :mode="mode" :booking-data="bookingData" />
</template>
