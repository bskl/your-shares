<script type="text/ecmascript-6">
    export default {
        /*
         * The component's name.
         */
        name: 'MainFooter',

        /*
         * The component's data.
         */
        data() {
            return {
                config: {
                    version: 'v0.0.1',
                }
            }
        },
    }
</script>

<template>
    <footer class="main-footer">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <p class="text-left">Copyright &copy; <router-link to="/">manage.yourShares</router-link></p>
                    </div>
                    <div class="col-6">
                        <p class="text-right">{{ config.version }}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
