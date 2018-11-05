<template>
    <div>
        <div class="mb-md comment-list">
            <div class="media media-sm small" v-for="comment in comments">
                <div class="media-left">
                    <img :src="comment.user.image" class="avatar">
                </div>
                <div class="media-body">
                    <div class="media-header">
                        <b>{{ comment.user.name }}</b>
                        <span class="text-muted"> - {{ fromNow(comment.created_at) }}</span>
                    </div>
                    <div v-html="comment.content"></div>
                </div>
                <div class="media-right">
                    <a @click="deleteComment(comment)" v-if="comment.user.id == currentUser.id">
                        <i class="icon-delete"></i>
                    </a>
                </div>
            </div>
        </div>
        <form action="#" @submit.prevent="save()">
            <div class="form-group">
                <editor ref="editor" :content="newComment.content" :placeholder="$t('comments.placeholder')"></editor>
            </div>
            <div class="text-right">
                <button class="btn btn-primary">{{ $t('comments.create') }}</button>
            </div>
        </form>
    </div>
</template>

<script>
    import Editor from '../common/Editor.vue'
    import {mapGetters} from 'vuex'

    export default {
        props: ['comments', 'url'],
        components: {
            Editor
        },
        computed: {
            ...mapGetters({
                currentUser: 'currentUser'
            }),
            innerComments() {
                return this.comments
            }
        },
        data() {
            return {
                newComment: {},
            }
        },
        methods: {
            save() {
                this.newComment.content = this.$refs.editor.getContent()
                axios.post(this.url + '/comments', this.newComment).then(res => {
                    this.innerComments.push(res.data)
                    this.newComment.content = ''
                    this.$refs.editor.setContent()
                    this.$emit('change', this.innerComments)
                })
            },
            deleteComment(comment) {
                axios.delete(this.url + '/comments/' + comment.id).then(() => {
                    let commentIndex = _.findIndex(this.innerComments, {id: comment.id})
                    this.innerComments.splice(commentIndex, 1)
                    this.$emit('change', this.innerComments)
                })
            }
        }
    }
</script>
