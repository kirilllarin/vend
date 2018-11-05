<template>
    <div>
        <div class="modal-header">
            <div class="modal-header-left">
                <a @click="destroy()">{{ $t('common.delete') }}</a>
            </div>
            <div class="modal-header-right">
                <a @click="edit()">{{ $t('common.edit') }}</a>
            </div>
            <div class="modal-title">{{ user.name }}</div>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <div class="avatar-upload">
                    <img :src="user.image" alt="" class="avatar-upload-image">
                </div>
            </div>
            <div class="text-center">
                <div class="form-group">
                    {{ user.email }}
                </div>
                <div class="form-group">
                    {{ $t('users.roles.' + user.role) }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        computed: {
            ...mapGetters({
                user: 'user'
            })
        },
        methods: {
            edit() {
                this.$root.$emit('showModal', 'edit-user')
            },
            destroy() {
                this.$store.dispatch('deleteUser', this.user).then(() => {
                    this.$root.$emit('hideModal')
                })
            }
        }
    }
</script>