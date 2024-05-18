<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm">
                <div :class="this.event_edit ? 'd-none' : 'd-block' ">
                    <form class="">
                        <div style="width:70%">
                            <input v-model="title" type="text" class="form-control mb-2" placeholder="Заголовок">
                        </div>
                        <div v-if="this.errors.title" class="text-danger" style="margin-top: -10px">{{
                                this.errors.title
                            }}
                        </div>
                        <div class="mb-2" style="width:80%">
                            <VueEditor v-model="content"
                                       useCustomImageHandler
                                       @image-added="handleImageAdded"
                                       :editor-toolbar="customToolbar"
                            />
                        </div>
                        <div ref="dropzone" class="h-auto pt-4 pb-4 mb-3 text-center border-dashed rounded bgd-gray"
                             style="cursor: pointer; max-width:30%">
                            UPLOAD IMAGES
                        </div>
                        <div v-if="this.errors.images" class="text-danger" style="margin-top: -10px">{{
                                this.errors.images
                            }}
                        </div>
                        <input @click.prevent="store" type="submit" class="btn btn-success mb-3" value="Отправить">
                    </form>
                </div>

                <!--EDIT-->
                <div :class="this.event_edit ? 'd-block' : 'd-none' ">
                    <form class="">
                        <div style="width:70%">
                            <input v-model="post.title" type="text" class="form-control mb-2" placeholder="title">
                        </div>
                        <div v-if="this.errors.title" class="text-danger" style="margin-top: -10px">{{
                                this.errors.title
                            }}
                        </div>
                        <div class="mb-2" style="width:80%">
                            <VueEditor v-model="post.content"
                                       useCustomImageHandler
                                       @image-added="handleImageAdded"
                                       @image-removed="handleImageRemoved"
                                       :editor-toolbar="customToolbar"
                            />
                        </div>
                        <div ref="dropzoneEdit" class="h-auto pt-4 pb-4 mb-3 text-center border-dashed rounded bgd-gray"
                             style="cursor: pointer; max-width:40%">
                            UPLOAD IMAGES
                        </div>
                        <div v-if="this.errors.images" class="text-danger" style="margin-top: -10px">{{
                                this.errors.images
                            }}
                        </div>
                        <input @click.prevent="update" type="submit" class="btn btn-success mb-3" value="Обновить">
                    </form>
                </div>
            </div>
            <div class="col-sm">
                <!--CONTENT-->
                <h2>ПОСЛЕДНЕЕ СООБЩЕНИЕ
                    <a @click.prevent="isEdit" href="#">
                        <BIconPencil/>
                    </a>
                </h2>

                <div v-if="spinner" class="z-2 p-1">
                    <b-spinner small variant="primary" label="Spinning"></b-spinner>
                </div>

                <div v-if="post" class="">
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
                            <td class="w-25">
                                Исходное изображение
                                <br/>
                                <a :href=image.url target="top">
                                    <img :src=image.url class="mb-2" style="width:70%" alt="">
                                </a>
                            </td>
                            <td class="w-25">
                                Миниатюра 100x100
                                <br/>
                                <a :href=image.prev_url target="top">
                                    <img :src=image.prev_url alt="">
                                </a>
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
            </div>
        </div>
    </div>

</template>

<script>
import {Dropzone} from "dropzone";
import {VueEditor} from "vue3-editor"
import {BIconPencil} from "bootstrap-icons-vue";

export default {
    name: "Dropzone",

    components: {
        BIconPencil,
        VueEditor
    },

    data() {
        return {
            dropzone: null,
            dropzoneEdit: null,
            title: '',
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            post: {},
            content: "",
            customToolbar: [
                [{size: ['small', false, 'large', 'huge']}],
                ["bold", "italic", "underline"],
                [
                    { align: "" },
                    { align: "center" },
                    { align: "right" },
                    { align: "justify" }
                ],
                [
                    {header: [1, 2, 3, ""]}


                ],
                [{list: "ordered"}, {list: "bullet"}],
                ["image"],
            ],
            errors: {
                images: null,
                title: null
            },
            event_edit: false,
            spinner: true,
            idImgDel: [], //удаление IMAGES из DROPZONE
            urlImgDel: [], //удаление IMAGES из VueEditor - handleImageRemoved
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

        this.dropzoneEdit = new Dropzone(this.$refs.dropzoneEdit, {
            url: '/api/posts',
            autoProcessQueue: false,
            addRemoveLinks: true,
            headers: {
                'x-csrf-token': this.csrf,
            },
            acceptedFiles: "image/jpeg,image/png,image/gif"
        })

        this.dropzoneEdit.on('removedfile', (file) => {

            this.idImgDel.push(file.id)
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

        },
        getPost() {
            axios.get('/api/posts')
                .then(res => {
                    this.spinner = false
                    this.post = res.data.data

                    this.setDropzoneEdit() //инициализация скрытого Dropzone для редактирования
                })
        },

        isEdit() {
            if (this.event_edit) {
                this.event_edit = false
            } else {
                this.event_edit = true
            }
        },

        setDropzoneEdit() {

            /*очистка поля DROPZONE от накопления изобажений при Update*/
            document.querySelectorAll(".dz-preview").forEach(el => el.remove())
            /*вариант 2*/
            //this.dropzoneEdit.previewsContainer.querySelectorAll(".dz-preview").forEach(el => el.remove())

            this.post.images.forEach(image => {
                let file = {id: image.id, name: image.name, size: image.size};
                this.dropzoneEdit.displayExistingFile(file, image.url);

            })

        },

        update() {

            const dataUpdate = new FormData()
            const files = this.dropzoneEdit.getAcceptedFiles()

            files.forEach(file => {
                dataUpdate.append('images[]', file)
                this.dropzoneEdit.removeFile(file)
            })

            /*для DROPZONE*/
            this.idImgDel.forEach(image => {
                dataUpdate.append('id_img_del[]', image)
            })

            /*для VueEditor*/
            this.urlImgDel.forEach(image => {
                dataUpdate.append('url_img_del[]', image)
            })

            dataUpdate.append('title', this.post.title)
            dataUpdate.append('content', this.post.content)
            dataUpdate.append('_method', 'PATCH')

            axios.post(`/api/posts/${this.post.id}`, dataUpdate)
                .then(res => {

                    this.event_edit = false
                    this.getPost()
                })
                .catch(error => {
                    if (error.response.data.errors) {
                        this.errors.images = (error.response.data.errors.images) ? error.response.data.errors.images[0] : null
                        this.errors.title = (error.response.data.errors.title) ? error.response.data.errors.title[0] : null
                    }
                })
        },

        /*Изображения сохраняются автоматически после добавления в форме - поправить*/
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
        },

        handleImageRemoved(url){
            this.urlImgDel.push(url)
        }


    }
}
</script>

<style>
.dz-success-mark, .dz-error-mark {
    display: none !important;;
}

.ql-editor p img {
    width: 50%;
}

</style>
