export default {
    data(){
        return {

        }
    }, 
    methods: {
        async callApi(method, url, dataObj ) {
            // if(dataObj != undefined) {
            //     url += '?filter=' + dataObj.filter
            // }
            // if(dataObj != undefined) {
            //     url += '?page=' + dataObj.page
            // }

            try {
              return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                console.log(e.response.data.errors);
                return e.response;
            }
        },
        makeToaster(text, title, variant) {
            this.$bvToast.toast(text, {
              title: title,
              variant: variant,
              toaster: 'b-toaster-top-right',
              static: true,
            })
          }

    }
}