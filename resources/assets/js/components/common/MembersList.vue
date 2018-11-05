<template>
    <div>
        <div class="media media-sm cursor-pointer" v-for="user in users" @click="toggleUser(user)">
            <div class="media-left">
                <i :class="inSelected(user) ? 'text-green icon-checked' : 'icon-unchecked'"></i>
            </div>
            <div class="media-body">
                <img :src="user.image" alt="" class="avatar avatar-sm">
                <b>{{ user.name }}</b><br>
                <span class="text-muted">{{ user.email }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            users: {},
            selected: {}
        },
        computed: {
            innnerSelected() {
                return this.selected
            }
        },
        methods: {
            inSelected(user) {
                return _.findIndex(this.innnerSelected, {id: user.id}) > -1
            },
            toggleUser(user) {
                if (this.inSelected(user)) {
                    this.innnerSelected.splice(_.findIndex(this.innnerSelected, {id: user.id}), 1)
                } else {
                    this.innnerSelected.push(user)
                }
                this.$emit('change', this.innnerSelected)
            }
        }
    }
</script>