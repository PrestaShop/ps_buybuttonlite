<template>
    <h3 class="ps-card-header">
        <slot/>
    </h3>
</template>

<script>
export default {
    name: 'PsCardHeader'
}
</script>

<style scoped lang="scss">
.ps-card-header {
    padding: 12px 20px !important;
    padding: .75rem 1.25rem !important;
    background-color: #f5f5f5 !important;
    border-bottom: 1px solid #e5e5e5 !important;
    font-size: 1rem !important;
    font-weight: 700;
    line-height: 1.2 !important;
    color: #363a41;
    margin: unset !important;
    margin-top: 0;
    height: unset !important;
    text-transform: unset !important;
    font-family: Open Sans,Helvetica,Arial,sans-serif !important;
}
</style>
