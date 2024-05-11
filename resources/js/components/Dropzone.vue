<template>
    <div class="w-25 border-dashed rounded-top">
        <div ref="dropzone" class="btn d-block text-center bg-light-subtle p-5">
            UPLOAD
        </div>
    </div>
</template>

<script>
import {Dropzone} from "dropzone";

export default {
    name: "Dropzone",

    data() {
        return {
            dropzone: null,
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {

            url: 'url'
        })
    }
}
</script>

<style scoped>

</style>
