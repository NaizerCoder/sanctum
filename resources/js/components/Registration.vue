<template>
    <div>

        <div class="input-group mb-3">
            <span class="input-group-text bg-primary"><i class="bi bi-person-plus-fill text-white"></i></span>
            <input v-model="name" @keyup.enter="register" type="text" class="form-control" placeholder="Имя">
        </div>
        <div v-if="this.errors.user.name" class="text-danger" style="margin-top: -10px">{{ this.errors.user.name }}</div>

        <div class="input-group mb-3">
            <span class="input-group-text bg-primary"><i class="bi bi-person-plus-fill text-white"></i></span>
            <input v-model="email" @keyup.enter="register" type="email" class="form-control" placeholder="E-mail">
        </div>
        <div v-if="this.errors.email" class="text-danger" style="margin-top: -10px">{{ this.errors.email }}</div>

        <div class="input-group mb-3">
            <span class="input-group-text bg-primary"><i class="bi bi-person-plus-fill text-white"></i></span>
            <input v-model="password" @keyup.enter="register" type="password" class="form-control" placeholder="Пароль">
        </div>
        <div v-if="this.errors.password" class="text-danger" style="margin-top: -10px">{{ this.errors.password }}</div>

        <div class="input-group mb-3">
            <span class="input-group-text bg-primary"><i class="bi bi-person-plus-fill text-white"></i></span>
            <input v-model="password_confirmation"  @keyup.enter="register" type="password" class="form-control" placeholder="Повторите пароль">
        </div>
        <div v-if="this.errors.password_confirmation" class="text-danger" style="margin-top: -10px">
            {{ this.errors.password_confirmation }}
        </div>

        <div v-if="this.errors.user.exist" class="text-danger" style="margin-top: -10px">{{ this.errors.user.exist }}</div>

        <input @click.prevent="register" type="submit" class="btn btn-primary" value="Регистрация">

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
            errors: {
                user: {
                    exist: null,
                    name: null,
                },
                email: null,
                password: null,
                password_confirmation: null,
            }
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

                            if (error.response.data.errors) {
                                this.errors.user.name = (error.response.data.errors.name) ? error.response.data.errors.name[0] : null
                                this.errors.email = (error.response.data.errors.email) ? error.response.data.errors.email[0] : null
                                this.errors.password = (error.response.data.errors.password) ? error.response.data.errors.password[0] : null
                                this.errors.password_confirmation = (error.response.data.errors.password_confirmation) ?  error.response.data.errors.password_confirmation[0] : null
                            }
                            if(error.response.data.error){
                                this.errors.user.name = this.errors.email = this.errors.password = this.errors.password_confirmation = null
                                this.errors.user.exist = error.response.data.error
                            }
                        })
                })
        }
    }
}
</script>

<style scoped>

</style>
