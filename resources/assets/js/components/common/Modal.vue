<template>
    <div class="modal" :class="{'in' : visible}" :style="visible ? 'display: block' : ''" @click="hide()">
        <div class="modal-dialog" @click.stop="">
            <div class="modal-content">
                <div class="modal-loading" v-show="loading"></div>
                <div class="alert alert-danger" v-if="error">{{ error }}</div>
                <component :is="view" ref="view" v-show=" ! loading"></component>
            </div>
        </div>
    </div>
</template>

<script>
    import ShowCard from '../cards/ShowCard.vue'
    import EditCard from '../cards/EditCard.vue'
    import EditProject from '../projects/EditProject.vue'
    import ShowUser from '../users/ShowUser.vue'
    import EditUser from '../users/EditUser.vue'
    import TopicForm from '../topics/TopicForm.vue'
    import ShowMessage from '../messages/ShowMessage.vue'
    import MessageForm from '../messages/MessageForm.vue'

    export default {
        name: 'modal',
        components: {
            ShowCard,
            ShowUser,
            EditUser,
            TopicForm,
            MessageForm,
            ShowMessage,
            EditCard,
            EditProject
        },
        data() {
            return {
                prefetch: [
                    'show-card'
                ],
                error: null,
                view: null,
                visible: false,
                loading: true
            }
        },
        mounted() {
            this.$root.$on('showModal', view => {
                this.view = view
                this.visible = true
                this.loading = false
                this.error = null
            })
            this.$root.$on('hideModal', () => {
                this.view = null
                this.visible = false
                this.loading = true
                this.error = null
            })

            document.addEventListener('keyup', this.hideByKey)
        },
        beforeDestroy() {
            document.removeEventListener('keyup', this.hideByKey)
        },
        methods: {
            hideByKey(e) {
                if (e.keyCode === 27) {
                    this.hide()
                }
            },
            hide(e) {
                this.visible = false
                this.view = null
                this.loading = true
            },
            show() {
                this.visible = true
            }
        }
    }
</script>
