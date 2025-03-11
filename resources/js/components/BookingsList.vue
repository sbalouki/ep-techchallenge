<template>
    <div>
        <h3 class="mb-3">List of client bookings</h3>
        <template v-if="bookings.length > 0">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="booking in internalBookings" :key="booking.id">
                        <td>{{ booking.start | moment("dddd Do MMMM YYYY, HH:ss") }} to {{ booking.end | moment("HH:ss") }}</td>
                        <td>{{ booking.notes }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>

        <template v-else>
            <p class="text-center">The client has no bookings.</p>
        </template>
    </div>
</template>

<script>
import { computed } from 'vue';

export default {
    props: {
        bookings: {
            type: Array,
            required: true
        }
    },
    computed: {
        internalBookings () {
            return this.bookings.sort((a, b) => new Date(b.start) - new Date(a.start))
        }
    },
    methods: {
        async deleteBooking(booking) {
            this.$emit('delete', booking)
        }
    }
}
</script>
