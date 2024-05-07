<template>

</template>

<script>
export default {
    name: "Get",

    mounted() {
        this.getData();
    },

    methods:{
        getData(){
            axios.get('/api/get')
                .then( res => {
                    //console.log(res);
                })
                .catch(error => {
                    console.log(error);
                })
        }
    }
}
</script>

<style scoped>

</style>
