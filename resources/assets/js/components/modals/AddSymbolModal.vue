<script type="text/ecmascript-6">
    import Modal from '../modals/modal/Modal.vue';
    import ModalHeading from '../modals/modal/ModalHeading.vue';
    import ModalBody from '../modals/modal/ModalBody.vue';
    import Spinner from '../loaders/Spinner.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'AddSymbolModal',

        components: {
            Modal, ModalHeading, ModalBody, Spinner,
        },

        /**
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    name: '',
                }),
                saving: false,
                showModal: false,
            };
        },

        /**
         * Prepare the component.
         */
        mounted() {
            document.addEventListener("keydown", (e) => {
                if (e.keyCode == 27) {
                    this.close();
                }
            });
        },

        methods: {
            /**
             * Open the model.
             */
            open() {
                this.showModal = true;
            },

            /**
             * Close the modal and reset form elements.
             */
            close() {
                this.showModal = false;
                this.saving = false;
                this.form.reset();
            },

            /**
             * Save the symbol and hide the modal.
             */
            saveSymbol() {
                this.saving = true;

                this.form.post('/symbol')
                    .then(response => {
                        Bus.$emit('symbolAdded', {
                            symbol: response.data
                        });

                        this.close();
                    }, error => {
                        this.saving = false;
                    })

            },
        },
    }
</script>

<template>
    <div @click="close" v-show="this.showModal">
        <modal width="360">
            <modal-heading>
                <span class="ft13">{{ $t("Add Symbol") }}</span>
            </modal-heading>
            <modal-body>
                <div v-if="saving">
                    <spinner />
                </div>
                <div v-else>
                    <form class="form-horizontal" role="form" method="POST"
                            v-on:submit.prevent="saveSymbol" v-on:keydown="form.errors.clear($event.target.name)">
                        <div class="form-group">
                            <div class="col-md-12"
                                    v-bind:class="{ 'has-danger': form.errors.has('name') }">
                                <input type="text" id="name" name="name" class="form-control" autofocus
                                        v-bind:placeholder="$t('Symbol Name')" v-model="form.name">
                                <label class="sr-only" for="name">{{ $t("Symbol Name") }}</label>

                                <span class="form-text text-danger"
                                        v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block"
                                        :disabled="form.errors.any()">{{ $t("Create") }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </modal-body>
        </modal>
    </div>
</template>

<style scoped>
    .ft13 { font-size: 1.3rem }
    .pa2 { padding: 2rem }
    .df { display: flex }
    .aic { align-items: center }
    .acc { align-content: center }
    .jcc { justify-content: center }
    .frame {
        margin-left: 2rem;
        margin-right: 2rem;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .mb2 { margin-bottom: 2rem }
    .lh2 { line-height: 2 }
    .basic-text { color: #424C55 }
    .tar { text-align: right }
</style>
