<template>
    <div>
        <h1 class="mb-6">Clients -> {{ internalClient.name }}</h1>

        <div class="flex">
            <div class="w-1/3 mr-5">
                <div class="w-full bg-white rounded p-4">
                    <h2>Client Info</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th class="text-gray-600 pr-3">Name</th>
                                <td>{{ internalClient.name }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Email</th>
                                <td>{{ internalClient.email }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Phone</th>
                                <td>{{ internalClient.phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Address</th>
                                <td>{{ internalClient.address }}<br/>{{ internalClient.postcode + ' ' + internalClient.city }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-2/3">
                <div>
                    <button class="btn" :class="{'btn-primary': currentTab == 'bookings', 'btn-default': currentTab != 'bookings'}" @click="switchTab('bookings')">Bookings</button>
                    <button class="btn" :class="{'btn-primary': currentTab == 'journals', 'btn-default': currentTab != 'journals'}" @click="switchTab('journals')">Journals</button>
                </div>

                <!-- Bookings -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'bookings'">
                    <bookings-list
                        :bookings="internalClient.bookings"
                        @delete="deleteBooking"
                    />
                </div>

                <!-- Journals -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'journals'">
                    <h3 class="mb-3">List of client journals</h3>

                    <p>(BONUS) TODO: implement this feature</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientShow',

    props: {
        client: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            currentTab: 'bookings',
            internalClient: null
        }
    },

    created () {
        this.internalClient = this.client
    },

    methods: {
        switchTab(newTab) {
            this.currentTab = newTab;
        },

        async deleteBooking(booking) {
            try {
                await axios.delete(`/bookings/${booking.id}`);
            } catch (error) {
                console.log(error)
                return
            }

            this.internalClient.bookings = this.internalClient.bookings.filter(b => b.id !== booking.id)
        }
    }
}
</script>
