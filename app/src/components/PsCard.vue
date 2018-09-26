<template>
    <div class="ps-card">
        <slot/>
    </div>
</template>

<script>
export default {
    name: 'PsCard'
}
</script>

<style scoped lang="scss">
.ps-card {
    position: relative;
    display: block;
    margin-bottom: 12px;
    margin-bottom: .75rem;
    background-color: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 0;
    font-family: Open Sans,Helvetica,Arial,sans-serif !important;
}
</style>
