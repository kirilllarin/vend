<template>
    <a href="#"
       @click.prevent="showCard(card)"
       class="card card-project"
       :data-id="card.id">
        <div class="card-body">
            <span class="card-title">{{ card.title }}</span>
            <div class="card-assigned">
                <img :src="card.assigned.image" class="avatar avatar-small" v-if="card.assigned">
            </div>
        </div>
        <div class="card-footer text-muted">
            <span v-if="card.tasks.length === 0 && card.is_completed">
                <i class="icon icon-checked text-green"></i>
            </span>
            <span v-if="card.tasks.length">
                <i class="icon"
                   :class="[{'text-green' : card.is_completed}, completedTaskCount == card.tasks.length || card.is_completed ? 'icon-checked' : 'icon-unchecked']"></i>
                {{ completedTaskCount }}/{{ card.tasks.length }}
            </span>
            <span v-if="card.files.length"><i class="icon-paper-clip"></i></span>
            <span v-if="card.comments.length"><i class="icon-bubbles"></i></span>
            <span v-if="card.logs.length"
                  :class="{'log-card-active': currentLog && currentLog.is_running && currentLog.card.id == card.id}">
                                    <i class="icon icon-clock"></i>
                                </span>
            <span class="label label-primary label-sm" v-if="card.due_date">
                {{ monthDateFormat(card.due_date) }}
            </span>
        </div>
    </a>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        props: ['card'],
        computed: {
            ...mapGetters({
                currentLog: 'currentLog'
            }),
            completedTaskCount() {
                return _.filter(this.card.tasks, {is_completed: true}).length
            }
        },
        methods: {
            showCard(card) {
                this.$store.commit('setCard', card)
                this.$root.$emit('showModal', 'show-card')
            }
        }
    }
</script>