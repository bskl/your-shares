<script type="text/ecmascript-6">
    export default {
        props: ['showModal', 'width'],

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
        <div class="modal fade" v-show="showModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content" :style="{width: modalWidth}">
                    <slot/>
                </div>
            </div>
        </div>
</template>
