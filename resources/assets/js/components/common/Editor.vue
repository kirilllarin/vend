<template>
    <div class="form-group">
        <div id="editor" v-html="content" class="form-control"></div>
    </div>
</template>

<script>
    import Quill from 'quill'

    export default {
        props: {
            content: {
                type: String,
                default: ''
            },
            placeholder: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                editor: null
            }
        },
        mounted() {
            let editorEl = this.$el.querySelectorAll('#editor')[0];
            this.editor = new Quill(editorEl, {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'link', 'code-block'],
                    ]
                },
                placeholder: this.placeholder,
                theme: 'snow'  // or 'bubble'
            })
        },
        methods: {
            getContent() {
                return this.editor.root.innerHTML
            },
            setContent(content) {
                this.editor.setContents(content || '')
            }
        }
    }
</script>