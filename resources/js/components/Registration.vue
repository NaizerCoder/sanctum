<template>
    <div>

        <input v-model="name" type="name" class="form-control mb-2 " placeholder="name">
        <input v-model="email" type="email" class="form-control mb-2 " placeholder="email">
        <input v-model="password" type="password" class="form-control mb-2" placeholder="password">
        <input v-model="password_confirmation" type="password" class="form-control mb-4" placeholder="password_confirmation">
        <input @click.prevent="register" type="submit" class="btn btn-primary" value="Register">

    </div>
</template>

<script>
export default {
    name: "Registration",
    data() {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
        }
    },

    methods: {
        register() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation
                    })
                        .then(response => {
                            localStorage.setItem('x-xsrf-token',response.config.headers['X-XSRF-TOKEN'])
                            this.$router.push({name: 'user.personal'})
                        })
                        .catch(error => {
                            console.log(error.response.data.message);
                        })
                })
        }
    }
}
</script>

<style scoped>

</style>
