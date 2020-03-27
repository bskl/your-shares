import { mapGetters } from "vuex";

export default {
  data() {
    return {
      waitFor: '',
    }
  },

  computed: {
    ...mapGetters([
      'isInLoading',
    ]),

    isLoading() {
      return this.isInLoading(this.waitFor);
    },
  },

  methods: {
    startLoading() {
      this.$store.dispatch('startLoadingBy', this.waitFor);
    },

    stopLoading() {
      this.$store.dispatch('stopLoadingBy', this.waitFor);
    },
  },
}
