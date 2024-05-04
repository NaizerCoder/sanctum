<template>
    <div class="w-25">

        <input v-model="email" type="email" class="form-control mb-2 " placeholder="login">
        <input v-model="password" type="password" class="form-control mb-4" placeholder="password">
        <input @click.prevent="login" type="submit" class="btn btn-primary" value="Login">


    </div>
</template>

<script>
export default {
    name: "Login",

    data(){
        return{
            email: null,
            password: null
        }
    },

    methods:{

        login(){

            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login',{email:this.email, password:this.password})
                    .then( res => {
                        console.log(res);
                    })
            });

        }

    }
}
</script>

<style scoped>

</style>
