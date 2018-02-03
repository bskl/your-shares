<script type="text/ecmascript-6">

    export default {
        props: {
            snackbar: {
                type: Object,
                default: function () {
                    return {
                        show: false,
                        timeout: 3000,
                        position_y: null,
                        position_x: null,
                        mode: '',
                        color: '',
                        text: '',
                    }
                }
            },
        },
        //props: ['snackbar', 'timeout', 'position_y', 'position_x', 'mode', 'color', 'text'],

        /*
         * The component's name.
         */
        name: 'Snackbar',

    }
</script>

<template>
    <v-card>
        <v-snackbar
            :timeout="snackbar.timeout"
            :top="snackbar.position_y === 'top'"
            :bottom="snackbar.position_y === 'bottom'"
            :right="snackbar.position_x === 'right'"
            :left="snackbar.position_x === 'left'"
            :multi-line="snackbar.mode === 'multi-line'"
            :vertical="snackbar.mode === 'vertical'"
            :color="snackbar.color"
            v-model="snackbar.show"
        >
            {{ snackbar.text }}
            <v-btn dark flat @click.native="snackbar.show = false">Close</v-btn>
        </v-snackbar>
    </v-card>
</template>