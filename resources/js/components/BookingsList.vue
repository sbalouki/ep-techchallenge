<template>
    <div>
        <h3 class="mb-3">List of client bookings</h3>

        <select class="form-control mb-3 w-25" v-model="selectedFilter" name="filterBookings" aria-label="Filter bookings by timeline">
            <option value="all">All bookings</option>
            <option value="previous">Previous bookings</option>
            <option value="future">Future bookings</option>
        </select>
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
export default {
    props: {
        bookings: {
            type: Array,
            required: true
        }
    },

    computed: {
        internalBookings () {
            return this.bookings
                .filter((booking) => this.filterBookings(booking))
                .sort((a, b) => new Date(b.start) - new Date(a.start))
        }
    },

    data () {
        return {
            selectedFilter: 'all'
        }
    },

    methods: {
        filterBookings(booking) {
            if (this.selectedFilter === 'previous') {
                return new Date(booking.start) < new Date()
            }

            if (this.selectedFilter === 'future') {
                return new Date(booking.start) > new Date()
            }

            return booking
        },
        async deleteBooking(booking) {
            this.$emit('delete', booking)
        }
    }
}
</script>
