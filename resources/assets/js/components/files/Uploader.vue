<template>
    <div class="uploader" :class="{'uploader-compact btn btn-default': compact}">
        <div class="uploader-text">
            <i class="icon-upload"></i>
            {{ text }}
        </div>
        <div class="uploader-loading" v-if="loading">
            <span class="uploader-loading-icon"></span>
            Uploading...
        </div>
        <div v-if="percentCompleted !== 0" class="uploader-percent">{{ percentCompleted + '%' }}</div>
        <input type="file" name="userfile"
               class="uploader-field"
               @change="uploadFile"
               multiple>
    </div>
</template>

<script>
    export default {
        props: ['type', 'parent', 'compact', 'text', 'single', 'uploaded'],
        data() {
            return {
                loading: false,
                percentCompleted: 0
            }
        },
        mounted() {

        },
        methods: {
            uploadFile(e) {
                const files = e.target.files
                this.loading = true
                for(let i = 0; i< files.length; i++) {
                    const formData = new FormData()
                    formData.append('userfile', files[i])
                    formData.append('type', this.type)
                    if(this.parent) {
                        formData.append('parent', this.parent)
                    }

                    const options =  {
                        onUploadProgress: (progressEvent) => {
                            this.percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        }
                    }

                    axios.post('upload', formData, options).then(res => {
                        this.uploaded(res.data)
                        this.percentCompleted = 0
                        this.loading = false
                    })
                }
            }
        }
    }
</script>