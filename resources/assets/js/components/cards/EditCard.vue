<template>
    <div>
        <form @submit.prevent="save()">
            <div class="modal-header">
                <div class="modal-title">{{ card.id ? $t('cards.edit') : $t('cards.create') }}</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{ $t('cards.form.title') }}</label>
                    <input type="text" name="title" :placeholder="$t('cards.form.title')" v-model="card.title" class="form-control form-control-lg">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{ $t('cards.form.assignedTo') }}</label>
                            <select v-model="card.assigned_to" class="form-control">
                                <option value="">None</option>
                                <option :value="user.id" v-for="user in users">{{ user.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{ $t('cards.form.dueDate') }}</label>
                            <datepicker v-model="card.due_date"></datepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <editor ref="editor" :content="card.description"></editor>
                </div>
            </div>
            <div class="modal-footer">
                <a @click="cancel()" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</template>

<script>
    import Datepicker from '../common/Datepicker.vue'
    import Editor from '../common/Editor.vue'
    import {mapGetters} from 'vuex'

    export default {
        components: {
            Datepicker,
            Editor
        },
        computed: {
            ...mapGetters({
                users: 'users',
                card: 'card'
            })
        },
        mounted() {

        },
        methods: {
            editCard(card) {
                this.$store.commit('setCard', card)
                riseEvent('showModal', 'edit-card')
            },
            cancel() {
                this.card.id
                    ? this.emit('showModal', 'show-card')
                    : this.emit('hideModal')
            },
            save() {
                this.card.description = this.$refs.editor.getContent()
                this.$store.dispatch('saveCard', this.card)
                    .then(() => this.emit('showModal', 'show-card'))
                    .catch(err => this.emit('modalError', err.response.data))
            }
        }
    }
</script>