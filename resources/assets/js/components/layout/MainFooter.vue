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
    <footer class="footer">
        <div class="container">
            <span>Copyright &copy; <router-link to="/">Your Shares</router-link></span>
            
            <span class="float-right">{{ config.version }}</span>
        </div>
    </footer>
</template>
