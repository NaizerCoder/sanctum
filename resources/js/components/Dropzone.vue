<template>
    <form class="w-25">

        <input v-model="title" type="text" class="form-control mb-2" placeholder="title">
        <div ref="dropzone" class="h-auto pt-4 pb-4 mb-3 text-center border-dashed rounded bgd-gray"
             style="cursor: pointer;">
            UPLOAD
        </div>
        <input @click.prevent="store" type="submit" class="btn btn-success" value="Send">

        <div v-if="post">
            <h3>{{post.title}}</h3>
            <div v-for="image in post.images">
                <img :src=image.url class="mb-2 w-25">
            </div>
        </div>
    </form>
</template>

<script>
import {Dropzone} from "dropzone";

export default {
    name: "Dropzone",

    data() {
        return {
            dropzone: null,
            title: '',
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            post:{}
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {

            url: '/api/posts',
            autoProcessQueue: false,
            addRemoveLinks:true,
            headers: {
                //"authorization": "Bearer " + localStorage.getItem('x-xsrf-token'),
                //'x-csrf-token': 'E7iznsPE6fbz7osaa2Hfm8f9yn0UDYQWxaHuby3p',
                'x-csrf-token': this.csrf,
                //'x-xsrf-token': localStorage.getItem('x-xsrf-token'),
            },

        })

        this.getPost()
    },
    methods: {
        store() {

            // axios.get('/sanctum/csrf-cookie')
            //     .then(response => {
                    //step 1
                    // axios.post('/api/posts', {'images': this.dropzone.getAcceptedFiles()})
                    //     .then(res => {
                    //         console.log(res)
                    //     })

                    //step 2
                    const data = new FormData()
                    const files = this.dropzone.getAcceptedFiles()
                    files.forEach(file =>{
                        data.append('images[]',file)
                        this.dropzone.removeFile(file)
                    })

                    data.append('title',this.title)
                    this.title=''

                    axios.post('/api/posts',data)
                        .then(res => {
                            this.getPost()
                        })
                // })


            //console.log(this.dropzone.getAcceptedFiles());
        },
        getPost(){
            axios.get('/api/posts')
                .then( res => {
                    this.post = res.data.data
                })
        }

    }
}
</script>

<style scoped>

</style>
