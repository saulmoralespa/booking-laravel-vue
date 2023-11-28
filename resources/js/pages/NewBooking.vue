<script>
import FormBooking from "../components/FormBooking.vue";
import {clientFetch} from "../helpers/index.js";

export default {
    components: {
        FormBooking,
    },
    data() {
        return {
            mode: 'create',
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
            await clientFetch('GET', null, `hotels`).then(data => {
                this.bookingData.hotels = data.data;
            });
        }
    }
};
</script>


<template>
    <form-booking :mode="mode" :booking-data="bookingData" />
</template>
