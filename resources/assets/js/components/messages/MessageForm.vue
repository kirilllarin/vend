<template>
    <form @submit.prevent="save()" class="message-form">
        <div class="modal-header">
            <div class="modal-title">{{ message.id ? $t('messages.edit') : $t('messages.create') }}</div>
        </div>
        <div class="modal-body">

            <error-message ref="errorMessage"></error-message>

            <editor ref="editor" :content="message.message" :placeholder="$t('messages.form.message')"></editor>
            <div class="media-grid">
                <file-attachment v-for="file in message.files" v-bind:key="file" :file="file"></file-attachment>
            </div>

            <uploader type="message" :compact="true" :uploaded="attachFile" :text="$t('uploader.attach')"></uploader>

            <a @click="showEventAttachment = true" class="btn btn-default">
                <i class="icon-calendar"></i>
                {{ $t('events.attach') }}
            </a>
        </div>
        <div class="modal-footer-meta" v-if="showEventAttachment || event.title">
            <div class="form-group">
                <label>{{ $t('events.title') }}</label>
                <input type="text" class="form-control" v-model="event.title">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>{{ $t('events.start') }}</label>
                    <div class="form-group">
                        <datepicker v-model="event.start" :has-time="true"></datepicker>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>{{ $t('events.end') }}</label>
                    <div class="form-group">
                        <datepicker v-model="event.end" :has-time="true"></datepicker>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{ $t('events.location') }}</label>
                <input type="text" class="form-control" v-model="event.location">
            </div>
            <a class="btn btn-default btn-sm" @click="clearEvent()">{{ $t('events.clearAttachments') }}</a>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">{{ $t('common.save') }}</button>
        </div>
    </form>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Uploader from '../files/Uploader.vue'
    import Datepicker from '../common/Datepicker.vue'
    import Editor from '../common/Editor.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'
    import FileAttachment from '../files/FileAttachment.vue'

    export default {
        components: {
            Uploader,
            Datepicker,
            ErrorMessage,
            FileAttachment,
            Editor
        },
        data() {
            return {
                showEventAttachment: false,
                defaultEvent: {
                    title: '',
                    start: null,
                    end: null,
                    location: ''
                }
            }
        },
        computed: {
            ...mapGetters({
                message: 'message'
            }),
            event() {
                return this.message.event
                    ? this.message.event
                    : this.defaultEvent
            }
        },
        methods: {
            save() {
                this.message.message = this.$refs.editor.getContent().replace("<p><br></p>", "")
                this.message.event = this.event

                this.$store.dispatch('saveMessage', {
                    topicId: this.$route.params.id,
                    message: this.message
                }).then(() => {
                    this.emit('hideModal')
                }).catch(err => {
                    this.setErrors(err)
                })
            },
            clearEvent() {
                this.event = Object.assign({}, this.defaultEvent)
                this.message.event = null
                this.showEventAttachment = false
            },
            attachFile(file) {
                this.message.files.push(file)
            }
        }
    }
</script>