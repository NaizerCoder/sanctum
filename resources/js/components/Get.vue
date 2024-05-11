<template>
    <div v-if="spinner" class="z-2 p-1">
        <b-spinner small variant="primary" label="Spinning"></b-spinner>
    </div>

    <div v-if="!spinner">
        <div v-if="data">
            {{ data }}
        </div>
    </div>

</template>

<script>
export default {
    name: "Get",

    data() {
        return {
            data: null,
            spinner: true,
        }
    },

    mounted() {
        this.getData();
    },

    methods: {
        getData() {
            axios.get('/api/get')
                .then(res => {
                    //console.log(res.data);
                    this.spinner = false
                    this.data = res.data.content
                })
                .catch(error => {
                    //console.log(error);
                })
        }
    }
}
</script>

<style scoped>

</style>
