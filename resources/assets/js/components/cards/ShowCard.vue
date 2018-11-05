<template>
    <div>
        <div class="modal-header">
            <div class="modal-header-left">
                <a href="#" @click.prevent="deleteCard()">{{ $t('common.delete') }}</a>
            </div>
            <div class="modal-header-right">
                <a href="#" @click.prevent="editCard()">{{ $t('common.edit') }}</a>
            </div>
            <div class="modal-title">{{ card.title }}</div>
            <div class="modal-meta">
                <span class="label label-dark" v-if="card.assigned">
                    <img :src="card.assigned.image" class="label-image"> {{ card.assigned.name }}
                </span>
                <span class="label label-dark" v-if="logsLength()">{{ elapsedFormat(logsLength()) }}</span>
                <span class="label label-primary" v-if="card.due_date">{{ dateFormat(card.due_date) }}</span>
                <a class="label" :class="{'label-dark' : ! isLogRunning(), 'label-warn' : isLogRunning()}" @click="toggleTimer(card)">
                    <i class="icon-clock"></i>{{ $t('logs.startTimer') }}
                    <span v-if="isLogRunning()">{{ elapsedFormat(timer) }}</span>
                </a>
            </div>
        </div>

        <div class="modal-body modal-body-highlight">
            <div v-html="card.description" v-if="card.description !== ''"></div>
        </div>

        <div class="modal-body">
            <tabs>
                <div slot="tabs">
                    <a data-target="#tasks" class="active">{{ $t('cards.tasks') }}</a>
                    <a data-target="#comments">{{ $t('cards.comments') }}</a>
                    <a data-target="#files">{{ $t('cards.files') }}</a>
                    <a data-target="#logs">{{ $t('cards.logs') }}</a>
                </div>
                <div slot="tabsContent">
                    <div class="tabs-panel active" id="tasks">
                        <task-list
                                :tasks="card.tasks"
                                :url="'projects/'+ card.project_id + '/cards/' + card.id" v-if="card.id"
                                @change="tasks => card.tasks = tasks">
                        </task-list>
                    </div>
                    <div class="tabs-panel" id="comments">
                        <comment-list
                                :comments="card.comments"
                                :url="'projects/'+ card.project_id + '/cards/' + card.id"
                                @change="comments => card.comments = comments">
                        </comment-list>
                    </div>
                    <div class="tabs-panel" id="files">
                        <uploader :uploaded="addFile" :parent="card.id" type="card" text="Upload files"></uploader>
                        <file-list :files="card.files" :small="true"></file-list>
                    </div>
                    <div class="tabs-panel" id="logs">
                        <logs-list :logs="card.logs" :small="true" :add="true" :card="card"></logs-list>
                    </div>
                </div>
            </tabs>
        </div>
    </div>

</template>

<script>
    import TaskList from '../tasks/TaskList.vue'
    import CommentList from '../comments/CommentList.vue'
    import FileList from '../files/FileList.vue'
    import LogsList from '../logs/LogsList.vue'
    import Tabs from '../common/Tabs.vue'
    import Uploader from '../files/Uploader.vue'
    import {mapGetters} from 'vuex'

    export default {
        name: 'show-card',
        components: {
            TaskList,
            FileList,
            LogsList,
            Uploader,
            Tabs,
            CommentList
        },
        data() {
            return {
                fetched: false
            }
        },
        computed: {
            ...mapGetters({
                card: 'card',
                timer: 'timer',
                currentLog: 'currentLog'
            })
        },
        mounted() {

        },
        methods: {
            addFile(file) {
                this.card.files.push(file)
            },
            logsLength() {
                return _.sumBy(this.card.logs, 'length')
            },
            editCard() {
                this.$root.$emit('showModal', 'edit-card')
            },
            deleteCard() {
                this.$store.dispatch('deleteCard', this.card)
                this.$root.$emit('hideModal')
            },
            toggleTimer(card) {
                this.$store.dispatch('toggleTimer', card)
            },
            isLogRunning() {
                return this.currentLog && this.currentLog.is_running && this.currentLog.card.id === this.card.id
            }
        }
    }
</script>
