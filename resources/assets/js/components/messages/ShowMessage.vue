<template>
    <div>
        <div class="modal-body">
            <message-item :message="message"></message-item>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import MessageItem from './MessageItem.vue'

    export default {
        components: {
            MessageItem
        },
        computed: {
            ...mapGetters({
                message: 'message'
            })
        },
        mounted() {

        }
    }
</script>
