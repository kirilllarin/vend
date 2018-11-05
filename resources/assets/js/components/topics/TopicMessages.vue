<template>
    <div>
        <topic-header :topic="topic"></topic-header>

        <div class="container">
            <div class="wbox">
                <message-item :message="message" v-for="message in messages" v-bind:key="message.id"></message-item>
                <loader ref="loader"></loader>
                <div class="no-record" v-if="empty && ! isLoading()">
                    {{ $t('messages.empty') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import mixin from '../../mixins'
    import TopicHeader from './TopicHeader.vue'
    import Loader from '../common/Loader.vue'
    import MessageItem from '../messages/MessageItem.vue'

    export default {
        components: {
            TopicHeader,
            MessageItem,
            Loader
        },
        computed: {
            ...mapGetters({
                topic: 'topic',
                messages: 'messages'
            })
        },
        data() {
            return {
                empty: false
            }
        },
        mounted() {
            this.fetch()
        },
        methods: {
            fetch() {
                this.$refs.loader.start()
                this.$store.dispatch('getTopics', this.$route.params.id).then(() => {
                    this.$store.dispatch('getTopicChildren', {topicId: this.$route.params.id, type: 'messages'}).then(() => {
                        this.$refs.loader.stop()
                    })
                })
            },
            isLoading() {
                return this.$refs.loader.loading;
            },
            setEmpty() {
                this.empty = this.messages.length === 0;
            }
        },
        watch: {
            '$route': 'fetch',
            'messages': 'setEmpty'
        }
    }
</script>
