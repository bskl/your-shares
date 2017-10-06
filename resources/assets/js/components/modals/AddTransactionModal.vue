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
        name: 'AddTransactionModal',

        components: {
            vSelect, Modal, ModalHeading, ModalBody, Spinner,
        },

        /**
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    portfolio_symbol_id: '',
                    type: '',
                    date: '',
                    share: '',
                    price: '',
                    commission: '',
                }),
                transactions: [
                    { value: 1, label: 'Buying' },
                    { value: 2, label: 'Sale' },
                ],
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
            open(portfolioSymbolId) {
                this.form = new Form({
                    portfolio_symbol_id: portfolioSymbolId,
                    type: '',
                    date: '',
                    share: '',
                    price: '',
                    commission: '',
                });
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
             * Save the transaction and hide the modal.
             */
            saveTransaction() {
                this.saving = true;

                this.form.post('/transaction')
                    .then(response => {
                        Bus.$emit('transactionAdded', {
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
            <span>{{ $t("Add Transaction") }}</span>
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
                        v-on:submit.prevent="saveTransaction" v-on:keydown="form.errors.clear($event.target.name)">
                    <div class="form-group">
                        <div class="col-md-12"
                             v-bind:class="{ 'has-danger': form.errors.has('type') }">
                            <v-select v-model="form.type" :options="transactions" :placeholder="$t('Select Transaction')"></v-select>

                            <label class="sr-only" for="type">{{ $t("Select Transaction") }}</label>

                            <span class="form-text text-danger"
                                  v-if="form.errors.has('type')" v-text="form.errors.get('type')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             v-bind:class="{ 'has-danger': form.errors.has('date') }">
                            <input type="text" id="date" name="date" class="form-control"
                                   v-bind:placeholder="$t('Select Date')" v-model="form.date"></v-select>

                            <label class="sr-only" for="date">{{ $t("Select Date") }}</label>

                            <span class="form-text text-danger"
                                  v-if="form.errors.has('date')" v-text="form.errors.get('date')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             v-bind:class="{ 'has-danger': form.errors.has('share') }">
                            <input type="text" id="share" name="share" class="form-control"
                                   v-bind:placeholder="$t('Enter Share Amount')" v-model="form.share"></v-select>

                            <label class="sr-only" for="share">{{ $t("Enter Share Amount") }}</label>

                            <span class="form-text text-danger"
                                  v-if="form.errors.has('share')" v-text="form.errors.get('share')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             v-bind:class="{ 'has-danger': form.errors.has('price') }">
                            <input type="text" id="price" name="price" class="form-control"
                                   v-bind:placeholder="$t('Enter Share Price')" v-model="form.price"></v-select>

                            <label class="sr-only" for="price">{{ $t("Enter Share Price") }}</label>

                            <span class="form-text text-danger"
                                  v-if="form.errors.has('price')" v-text="form.errors.get('price')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             v-bind:class="{ 'has-danger': form.errors.has('commission') }">
                            <input type="text" id="commission" name="commission" class="form-control"
                                   v-bind:placeholder="$t('Enter Commission Rate')" v-model="form.commission"></v-select>

                            <label class="sr-only" for="commission">{{ $t("Enter Commission Rate") }}</label>

                            <span class="form-text text-danger"
                                  v-if="form.errors.has('commission')" v-text="form.errors.get('commission')"></span>
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
