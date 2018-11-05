<template>
    <div>
        <form @submit.prevent="save()">
            <div class="modal-header">
                <div class="modal-header-left">
                    <a @click="destroy()" onclick="return confirm('Are you sure to delete?')" v-if="project.id">{{ $t('common.delete') }}</a>
                </div>
                <div class="modal-header-right">
                    <a @click="project.is_archive = !project.is_archive">
                        {{ $t('projects.isArchive') }}
                    </a>
                </div>
                <div class="modal-title">{{ project.id ? $t('projects.edit') : $t('projects.create') }}</div>
            </div>
            <div class="modal-body">

                <error-message ref="errorMessage"></error-message>

                <div class="form-group">
                    <label class="form-label">{{ $t('projects.form.title') }}</label>
                    <input type="text" name="title" class="form-control form-control-lg" v-model="project.title">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ $t('projects.form.dueDate') }}</label>
                    <datepicker v-model="project.due_date"></datepicker>
                </div>
                <tabs>
                    <div slot="tabs">
                        <a data-target="#columns" class="active">{{ $t('projects.columns') }}</a>
                        <a data-target="#members">{{ $t('projects.members') }}</a>
                    </div>
                    <div slot="tabsContent">
                        <div class="tabs-panel active" id="columns">
                            <project-columns :columns="project.columns" @updated="columns => project.columns = columns"></project-columns>
                        </div>
                        <div class="tabs-panel" id="members">
                            <member-list :users="users" :selected="project.users" @change="selected => project.users = selected"></member-list>
                        </div>
                    </div>
                </tabs>
            </div>
            <div class="modal-footer">
                <a @click="cancel()" class="btn btn-default">{{ $t('common.cancel') }}</a>
                <button class="btn btn-primary">{{ $t('common.save') }}</button>
            </div>
        </form>
    </div>
</template>

<script>

    import sortable from 'jquery-ui/ui/widgets/sortable'
    import {mapGetters} from 'vuex'
    import ProjectColumns from '../projects/ProjectColumns.vue'
    import MemberList from '../common/MembersList.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'
    import Datepicker from '../common/Datepicker.vue'
    import Tabs from '../common/Tabs.vue'

    export default {
        components: {
            Tabs,
            Datepicker,
            ProjectColumns,
            ErrorMessage,
            MemberList
        },
        computed: {
            ...mapGetters({
                project: 'project',
                users: 'users'
            })
        },
        mounted() {

        },
        methods: {
            save() {
                this.$store.dispatch('saveProject', this.project)
                    .then(project => {
                        this.emit('hideModal')
                        this.$router.push({name: 'project', params: {id: project.id}})
                    })
                    .catch(err => this.setErrors(err))
            },
            cancel() {
                this.emit('hideModal')
            },
            destroy() {
                this.$store.dispatch('deleteProject', this.project).then(() => {
                    this.$router.push({name: 'projects'})
                })
            }
        }
    }
</script>
