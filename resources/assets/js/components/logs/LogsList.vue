<template>
    <div>
        <div v-if="add" class="form-inline">
            <div class="form-group">
                <input type="number" v-model="log.length" class="form-control" :placeholder="$t('logs.length')">
            </div>
            <div class="form-group">
                <datepicker v-model="log.date" :placeholder="$t('logs.date')"></datepicker>
            </div>
            <div class="form-group form-group-btn">
                <a @click="saveLog()" class="btn btn-primary">{{ $t('common.save') }}</a>
            </div>
        </div>
        <table class="table">
            <tr v-for="log in logs">
                <td width="5%">
                    <div class="label label-primary">{{ elapsedFormat(log.length) }}</div>
                </td>
                <td width="35%">
                    <b>{{ log.card.title }}</b><br>
                    <span class="text-muted">{{ dateFormat(log.created_at) }}</span>
                </td>
                <td width="2%"><img :src="log.user.image" alt="" class="avatar" v-if="log.user"></td>
                <td width="58%">
                    <a @click="deleteLog(log)" class="pull-right" v-if="log.user.id == currentUser.id">
                        <i class="icon-delete"></i>
                    </a>
                    <div v-if="log.user">
                        {{ log.user.name }}<br>
                        <span class="text-muted">{{ log.user.email }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    import Datepicker from '../common/Datepicker.vue'
    import {mapGetters} from 'vuex'

    export default {
        props: ['logs', 'small', 'add', 'card'],
        components: {
            Datepicker
        },
        data() {
            return {
                log: {}
            }
        },
        computed: {
            ...mapGetters({
                currentUser: 'currentUser'
            }),
            innerLogs() {
                return this.logs
            }
        },
        methods: {
            saveLog() {
                this.log.card_id = this.card.id
                this.log.length = parseInt(this.log.length)
                axios.post('logs', this.log).then(res => {
                    this.log = {};
                    this.innerLogs.push(res.data)
                    this.$emit('changed', this.innerLogs)
                })
            },
            deleteLog(log) {
                axios.delete('logs/' + log.id).then(() => {
                    let logIndex = _.findIndex(this.logs, {id: log.id})
                    this.innerLogs.splice(logIndex, 1)
                    this.$emit('changed', this.innerLogs)
                })
            }
        }
    }
</script>