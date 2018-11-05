<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">{{ $t('topics.index') }}</div>
            <div class="title-bar-right">
                <a @click="createTopic()" class="btn btn-primary title-bar-btn">{{ $t('topics.create') }}</a>
            </div>
        </div>
        <div class="container">
            <loader ref="loader"></loader>

            <div v-if="empty" class="no-record">{{ $t('topics.empty') }}</div>

            <div v-if="loaded">
                <div v-if="unreadTopics.length">
                    <h4>{{ $t('topics.unread') }}</h4>
                    <div class="wbox">
                        <table class="table">
                            <tr v-for="topic in unreadTopics"
                                @click="$router.push({name:'topic', params: {id: topic.id}})"
                                class="cursor-pointer">
                                <td width="25%">
                                    <b>{{ topic.title }}</b>
                                </td>
                                <td width="2%">
                                    <div v-if="topic.latest_message">
                                        <img :src="topic.latest_message.user.image" alt="" class="avatar">
                                    </div>
                                </td>
                                <td width="83%">
                                    <div v-if="topic.latest_message" class="text-more small">
                                        <b>{{ topic.latest_message.user.name }}</b>
                                        <span class="text-muted">{{ fromNow(topic.latest_message.created_at) }}</span>
                                        <div>{{ topic.latest_message.excerpt }}</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div v-if="readTopics.length">
                    <h4>{{ $t('topics.read') }}</h4>
                    <div class="wbox">
                        <table class="table">
                            <tr v-for="topic in readTopics"
                                @click.prevent="$router.push({name:'topic', params: {id: topic.id}})"
                                class="cursor-pointer">
                                <td width="25%">
                                    <b>{{ topic.title }}</b>
                                </td>
                                <td width="2%">
                                    <div v-if="topic.latest_message">
                                        <img :src="topic.latest_message.user.image" alt="" class="avatar">
                                    </div>
                                </td>
                                <td width="83%">
                                    <div v-if="topic.latest_message" class="text-more small">
                                        <b>{{ topic.latest_message.user.name }}</b>
                                        <span class="text-muted">{{ fromNow(topic.latest_message.created_at) }}</span>
                                        <div>{{ topic.latest_message.excerpt }}</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Loader from '../common/Loader.vue'

    export default {
        components: {
            Loader
        },
        computed: {
            ...mapGetters({
                topics: 'topics',
            }),
            readTopics() {
                return this.topics.filter(t => {
                    return ! t.unread
                })
            },
            unreadTopics() {
                return this.topics.filter(t => {
                    return t.unread
                })
            }
        },
        data() {
            return {
                loaded: false,
                empty: false
            }
        },
        mounted() {
            this.$refs.loader.start()
            this.$store.dispatch('getTopics').then(() => {
                this.$refs.loader.stop()
                this.loaded = true
                if(this.topics.length === 0) {
                    this.empty = true
                }
            })
        },
        methods: {
            createTopic() {
                this.$root.$emit('showModal', 'topic-form')
                this.$store.commit('setTopic', {users: []})
            }
        }
    }
</script>