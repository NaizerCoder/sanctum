<template>
    <div>

        <div class="input-group mb-3">
            <span class="input-group-text bg-primary"><i class="bi bi-person-plus-fill text-white"></i></span>
            <input v-model="email" type="email" class="form-control" placeholder="логин">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text bg-primary"><i class="bi bi-person-plus-fill text-white"></i></span>
            <input v-model="password" type="password" class="form-control" placeholder="пароль">
        </div>

        <div v-if="error" class="text-danger" style="margin-top: -10px">{{ error }}</div>
        <input @click.prevent="login" type="submit" class="btn btn-primary" value="Войти">

    </div>
</template>

<script>
export default {
    name: "Login",

    data() {
        return {
            email: null,
            password: null,
            error: null
        }
    },

    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('/login', {email: this.email, password: this.password})
                        .then(res => {
                            //console.log(res);
                            localStorage.setItem('x-xsrf-token', res.config.headers['X-XSRF-TOKEN'])
                            this.$router.push({name: 'user.personal'})
                        })
                        .catch(error => {
                            //console.log(error.response);
                            if (error.response.data.message) this.error = "проверьте актуальность логина или пароля"
                        })
                })
        }
    }
}
</script>

<style scoped>

</style>
