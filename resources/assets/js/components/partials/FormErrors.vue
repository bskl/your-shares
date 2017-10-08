<script type="text/ecmascript-6">

    export default {
        props: ['errors'],

        /*
         * The component's name.
         */
        name: 'FormErrors',

    }
</script>

<template>
    <div class="col-md-12" v-if="errors.any()">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <dl class="mb-0">
                <template v-for="errorItem in errors.errors">
                    <dd v-for="error in errorItem" :key="error.id">{{ error }}</dd>
                </template>
            </dl>
        </div>
    </div>
</template>