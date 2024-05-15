<template>
    <form v-if="this.event_edit" class="w-50">
        <div w-25>
            <input v-model="title" type="text" class="form-control mb-2" placeholder="title">
        </div>
        <div v-if="this.errors.title" class="text-danger" style="margin-top: -10px">{{ this.errors.title }}</div>
        <div class="mb-2">
            <VueEditor v-model="content"
                       useCustomImageHandler
                       @image-added="handleImageAdded"
                       :editor-toolbar="customToolbar"
            />
        </div>
        <div ref="dropzone" class="h-auto pt-4 pb-4 mb-3 w-25 text-center border-dashed rounded bgd-gray"
             style="cursor: pointer;">
            UPLOAD IMAGES
        </div>
        <div v-if="this.errors.images" class="text-danger" style="margin-top: -10px">{{ this.errors.images }}</div>
        <input @click.prevent="store" type="submit" class="btn btn-success mb-3" value="Отправить">
    </form>

    <form v-if="this.event_edit === false" class="w-50">
        <div w-25>
            <input v-model="post.title" type="text" class="form-control mb-2"  placeholder="title">
        </div>
        <div v-if="this.errors.title" class="text-danger" style="margin-top: -10px">{{ this.errors.title }}</div>
        <div class="mb-2">
            <VueEditor v-model="content"
                       useCustomImageHandler
                       @image-added="handleImageAdded"
                       :editor-toolbar="customToolbar"
            />
        </div>
        <div ref="dropzone" class="h-auto pt-4 pb-4 mb-3 w-25 text-center border-dashed rounded bgd-gray"
             style="cursor: pointer;">
            UPLOAD IMAGES
        </div>
        <div v-if="this.errors.images" class="text-danger" style="margin-top: -10px">{{ this.errors.images }}</div>
        <input @click.prevent="store" type="submit" class="btn btn-success mb-3" value="Отправить">
    </form>

    <h2>ПОСЛЕДНЕЕ СООБЩЕНИЕ</h2>
    <a @click.prevent="eventEdit" href="#">EDIT</a>

    <div v-if="post">
        <table class="table table-bordered">
            <tr>
                <td colspan="2">
                    <h4>{{ post.title }}</h4>
                </td>
            </tr>
            <tr v-if="post.images">
                <td colspan="2" class="text-center">Изображения DROPBOX</td>
            </tr>
            <tr v-for="image in post.images">
                <td>
                    Исходное изображение
                    <br/>
                    <img :src=image.url class="mb-2" style="width:50%" alt="">
                </td>
                <td>
                    Миниатюра 100x100
                    <br/>
                    <img :src=image.prev_url alt="">
                </td>
            </tr>
            <tr v-if="post.content">
                <td colspan="2" class="text-center">
                    Контент
                    <div class="ql-editor" v-html="post.content"></div>
                </td>

            </tr>
        </table>

    </div>

</template>

<script>
import {Dropzone} from "dropzone";
import {VueEditor} from "vue3-editor"

export default {
    name: "Dropzone",

    components: {
        VueEditor
    },

    data() {
        return {
            dropzone: null,
            title: '',
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            post: {},
            content: "",
            customToolbar: [
                [{size: ['small', false, 'large', 'huge']}],
                ["bold", "italic", "underline"],
                [{'align': [false]}],
                [{'align': ['center']}],
                [{'align': ['right']}],
                [{header: [1, 2, 3, 4, 5, 6, false]}],
                [{list: "ordered"}, {list: "bullet"}],
                ["image"],
            ],
            errors: {
                images: null,
                title: null
            },
            event_edit: true,
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/posts',
            autoProcessQueue: false,
            addRemoveLinks: true,
            headers: {
                'x-csrf-token': this.csrf,
            },
            acceptedFiles: "image/jpeg,image/png,image/gif"
        })
        this.getPost()
    },
    methods: {
        store() {
            const data = new FormData()
            const files = this.dropzone.getAcceptedFiles()

            files.forEach(file => {
                data.append('images[]', file)
                this.dropzone.removeFile(file)
            })

            data.append('title', this.title)
            data.append('content', this.content)
            this.title = ''
            this.content = ''

            axios.post('/api/posts', data)
                .then(res => {
                    this.getPost()
                })
                .catch(error => {
                    if (error.response.data.errors) {
                        this.errors.images = (error.response.data.errors.images) ? error.response.data.errors.images[0] : null
                        this.errors.title = (error.response.data.errors.title) ? error.response.data.errors.title[0] : null
                    }
                })

            //console.log(this.dropzone.getAcceptedFiles());
        },
        getPost() {
            axios.get('/api/posts')
                .then(res => {
                    this.post = res.data.data
                })
        },

        eventEdit(){
            if(this.event_edit){
                this.event_edit = false
            }
            else if(!this.event_edit){
                this.event_edit = true
            }

            console.log(this.event_edit)
        },

        handleImageAdded: function (file, Editor, cursorLocation, resetUploader) {

            const formData = new FormData();
            formData.append("image", file);

            axios.post('/api/posts/images', formData)
                .then(result => {
                    const url = result.data.url; // Get url from response
                    Editor.insertEmbed(cursorLocation, "image", url);
                    resetUploader();
                })
                .catch(err => {
                    console.log(err);
                });
        }

    }
}
</script>

<style scoped>
.dz-success-mark,
.dz-error-mark {
    display: none;
}

.ql-editor p img{
    display: none !important;
}

</style>
