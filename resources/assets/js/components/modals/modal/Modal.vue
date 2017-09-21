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
        <div class="modal modal-mask" style="display:block;" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" :style="{width: modalWidth}">
                    <slot/>
                </div>
            </div>
        </div>
    </transition>
</template>

<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .7);
        display: table;
    }
</style>