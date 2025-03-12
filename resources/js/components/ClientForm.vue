<template>
    <div>
        <h1 class="mb-6">Clients -> Add New Client</h1>

        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="name">Name</label>
                <ValidationProvider rules="required|max:190" v-slot="{ errors }">
                    <input type="text" id="name" class="form-control" v-model="client.name">
                    <span class="text-danger">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <ValidationProvider rules="required|email" v-slot="{ errors }">
                    <input type="text" id="email" class="form-control" v-model="client.email">
                    <span class="text-danger">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <ValidationProvider :rules="{ regex: /^[0-9 +]+$/, required: true }" v-slot="{ errors }">
                    <input type="text" id="phone" class="form-control" v-model="client.phone">
                    <span class="text-danger">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <div class="form-group">
                <label for="name">Address</label>
                <ValidationProvider rules="required" v-slot="{ errors }">
                    <input type="text" id="address" class="form-control" v-model="client.address">
                    <span class="text-danger">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <div class="flex">
                <div class="form-group flex-1">
                    <label for="city">City</label>
                    <ValidationProvider rules="required" v-slot="{ errors }">
                        <input type="text" id="city" class="form-control" v-model="client.city">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <div class="form-group flex-1">
                    <label for="postcode">Postcode</label>
                    <ValidationProvider rules="required" v-slot="{ errors }">
                        <input type="text" id="postcode" class="form-control" v-model="client.postcode">
                        <span class="text-danger">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </div>

            <div class="text-right">
                <a href="/clients" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { required, email, regex, max } from 'vee-validate/dist/rules';
import { extend } from 'vee-validate';
extend('required', {
    ...required, 
    message: 'The field is required'
});
extend('email', {
    ...email,
    message: 'The field should be a valid email address'
});
extend('regex', regex);
extend('max', max);

export default {
    name: 'ClientForm',

    components: {
        ValidationProvider,
        ValidationObserver
    },

    data() {
        return {
            client: {
                name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                postcode: '',
            }
        }
    },

    methods: {
        async storeClient() {
            console.log('fez')
            let data = await axios.post('/clients', this.client)

            window.location.href = data.data.client.url;
        }
    }
}
</script>
