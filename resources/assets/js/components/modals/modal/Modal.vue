<script type="text/ecmascript-6">
    export default {
        props: ['dialog', 'width'],

        /**
         * The component's data.
         */
        data() {
            return {
                dialogWidth: `${this.width}px`,
            }
        },
    }
</script>

<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent :max-width="dialogWidth">
            <v-card>
                <slot/>
            </v-card>
        </v-dialog>
    </v-layout>
</template>
