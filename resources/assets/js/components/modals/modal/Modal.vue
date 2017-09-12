<script type="text/ecmascript-6">
    export default {
        props: ['width'],

        /**
         * The component's data.
         */
        data() {
            return {
                modalWidth: `${this.width}px`
            }
        },
    }
</script>

<template>
    <transition name="fade">
        <div class="modal">
            <div class="modal-content" :style="{width: modalWidth}" @click.stop>
                <slot/>
            </div>
        </div>
    </transition>
</template>

<style scoped>
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(89, 91, 93, 0.5);
        display: flex;
        align-items: center;
        align-content: center;
        justify-content: center;
    }
    .modal-content {
        background-color: #F8F8F8;
        border-radius: 6px;
        min-width: 400px;
        overflow: hidden;
        margin-top: 10rem;
        align-self: flex-start;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
    }
</style>
