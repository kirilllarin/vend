<template>
    <div class="container">
        <h4></h4>
        <div class="wbox">
            <div class="wbox-content">
                <div class="form-group">
                    <div class="text-muted">{{ $t('account.yourAccount') }}</div>
                    <h3>{{ account.title }}</h3>
                </div>
                <div class="form-group">
                    <div class="text-muted">{{ $t('account.expireAt') }}</div>
                    <b>{{ dateFormat(account.expire_at) }}</b>
                </div>
                <div class="form-group">
                    <div class="text-muted">{{ $t('account.package') }}</div>
                    <b>{{ account.package }}</b>
                </div>
            </div>
        </div>

        <h4>Payments</h4>
        <div class="wbox">
            <div class="wbox-content">

            </div>
        </div>
    </div>
</template>

<script>
    import mixins from '../../mixins'

    export default {
        mixins: [mixins],
        data() {
            return {
                account: {}
            }
        },
        mounted() {
            axios.get('account').then(res => {
                this.account = res.data
            })
        }
    }
</script>