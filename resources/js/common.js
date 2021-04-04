export default {
    data(){
        return {

        }
    }, 
    methods: {
        async callApi(method, url, dataObj ) {
            try {
              return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.response
            }
        },
        makeToaster(append = false) {
            this.$bvToast.toast(`This is toast number`, {
              title: 'BootstrapVue Toast',
              variant: 'success',
              toaster: 'b-toaster-top-right',
              static: true,
              noAutoHide : true
            })
          }

    }
}