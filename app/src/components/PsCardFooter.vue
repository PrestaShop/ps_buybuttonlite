<template>
    <div class="ps-card-footer">
        <div class="d-flex justify-content-start">
            <slot name="footerLeft"></slot>
        </div>
        <div class="d-flex justify-content-end">
            <slot name="footerRight"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PsCardFooter'
}
</script>

<style scoped lang="scss">
.ps-card-footer {
    padding: .75rem 1.25rem;
    background-color: #f5f5f5;
    border-top: 1px solid #e5e5e5;
    font-family: Open Sans,Helvetica,Arial,sans-serif !important;
}
</style>
