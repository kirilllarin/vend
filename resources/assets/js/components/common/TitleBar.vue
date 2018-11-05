<template>
    <div class="title-bar">
        <div class="title-bar__title">
            <slot name="titleBarTitle"></slot>
        </div>
        <div class="title-bar__actions">
            <slot name="titleBarActions"></slot>
        </div>
    </div>
</template>

<script>
    export default {}
</script>