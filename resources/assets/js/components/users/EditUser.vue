<template>
    <div>
        <div class="modal-header">
            <div class="modal-title">{{ user.id ? $t('users.edit')  : $t('users.create') }}</div>
        </div>
        <div class="modal-body">

            <error-message ref="errorMessage"></error-message>

            <form @submit.prevent="save()">
                <div class="form-group text-center" v-if="user.id">
                    <div class="mb-md">
                        <img :src="user.image" alt="" class="avatar-upload-image">
                    </div>
                    <uploader
                            type="user"
                            :compact="true"
                            :uploaded="switchImage"
                            :text="$t('users.uploadFile')"
                            :single="true"></uploader>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ $t('users.form.name') }}</label>
                    <input type="text" name="name" class="form-control" v-model="user.name">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ $t('users.form.email') }}</label>
                    <input type="email" name="email" class="form-control" v-model="user.email">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ $t('users.form.role') }}</label>
                    <select name="role" class="form-control" v-model="user.role">
                        <option value="admin">{{ $t('users.roles.admin') }}</option>
                        <option value="editor">{{ $t('users.roles.editor') }}</option>
                        <option value="client">{{ $t('users.roles.client') }}</option>
                    </select>
                </div>
                <div class="divider"></div>
                <h4 v-if="user.id">{{ $t('users.form.changePassword') }}</h4>
                <div class="form-group">
                    <label class="form-label">{{ $t('users.form.password') }}</label>
                    <input type="password" name="password" class="form-control" v-model="user.password">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ $t('users.form.passwordConfirmation') }}</label>
                    <input type="password" name="password_confirm" class="form-control" v-model="user.password_confirm">
                </div>
                <div class="text-right">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Uploader from '../files/Uploader.vue'
    import ErrorMessage from '../common/ErrorMessage.vue'

    export default {
        components: {
            Uploader,
            ErrorMessage
        },
        computed: {
            ...mapGetters({
                user: 'user'
            })
        },
        methods: {
            save() {
                this.$store.dispatch('saveUser', this.user)
                    .then(() => this.$root.$emit('hideModal'))
                    .catch(err => this.setErrors(err))
            },
            switchImage(file) {
                this.user.image = file.thumbnail;
            }
        }
    }
</script>