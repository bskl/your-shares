<script type="text/ecmascript-6">
    import _ from 'lodash';
    import vSelect from 'vue-select';
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
            vSelect, Modal, ModalHeading, ModalBody, Spinner,
        },

        /**
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    symbol_id: '',
                    portfolio_id: '',
                }),
                symbols: [],
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
            open(portfolioId) {
                this.form = new Form({
                    symbol_id: '',
                    portfolio_id: portfolioId,
                });
                this.showModal = true;
            },

            /**
             * Close the modal and reset form elements.
             */
            close() {
                this.showModal = false;
                this.saving = false;
                this.symbols = [];
                this.form.reset();
            },

            /**
             * Search symbols.
             */
            searchSymbol(search, loading) {
                loading(true)
                this.getSymbol(search, loading, this)
            },

            getSymbol: _.debounce((search, loading, vm) => {
                axios.get('/symbol/search', {
                    params: {
                        q: search
                    }
                }).then(response => {
                    vm.symbols = response.data
                    loading(false)
                })
            }, 250),

            /**
             * Save the symbol and hide the modal.
             */
            saveSymbol() {
                this.saving = true;

                this.form.post('/portfolio/add-symbol')
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
    <modal width="360" v-show="this.showModal">
        <modal-heading>
            <span>{{ $t("Add Symbol") }}</span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close">
                <span aria-hidden="true">&times;</span>
            </button>
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
                             v-bind:class="{ 'has-danger': form.errors.has('symbol_id') }">
                            <v-select
                                v-model="form.symbol_id"
                                @search="searchSymbol"
                                :options="symbols"
                                :placeholder="$t('Search Symbol')"
                                label="code"
                            >
                            </v-select>

                            <label class="sr-only" for="symbol_id">{{ $t("Search Symbol") }}</label>

                            <span class="form-text text-danger"
                                  v-if="form.errors.has('symbol_id')" v-text="form.errors.get('symbol_id')"></span>
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
</template>
