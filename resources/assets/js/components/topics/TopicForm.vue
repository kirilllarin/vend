<template>
    <div>
        <form @submit.prevent="save()">
            <div class="modal-header">
                <div class="modal-header-right" v-if="topic.id">
                    <a @click="deleteTopic()">{{ $t('common.delete') }}</a>
                </div>
                <div class="modal-title">{{ topic.id ? $t('topics.edit') : $t('topics.create') }}</div>
            </div>
            <div class="modal-body">
                <error-message ref="errorMessage"></error-message>
                <div class="form-group">
                    <label class="form-label">{{ $t('topics.form.title') }}</label>
                    <input type="text" name="title" class="form-control form-control-lg" v-model="topic.title">
                </div>
                <div>
                    <label class="form-label">{{ $t('topics.form.members') }}</label>
                    <members-list :users="users" :selected="topic.users" @change="selected => topic.users = selected"></members-list>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">{{ $t('common.save') }}</button>
            </div>
        </form>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import MembersList from '../common/MembersList.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'

    export default {
        components: {
            MembersList,
            ErrorMessage
        },
        computed: {
            ...mapGetters({
                topic: 'topic',
                users: 'users'
            })
        },
        methods: {
            save() {
                this.$store.dispatch('saveTopic', this.topic).then(topic => {
                    this.$root.$emit('hideModal')
                    this.$router.push({name: 'topic', params: {id: topic.id}})
                }).catch(err => {
                    this.setErrors(err)
                })
            },
            deleteTopic() {
                this.$store.dispatch('deleteTopic', this.topic).then(() => {
                    this.$root.$emit('hideModal')
                    this.$router.push({name: 'topics'})
                })
            }
        }
    }
</script>
