export default {

    async login(_this) {
        await axios.post("api/auth/login", _this.loginForm)
            .then((response) => {
                localStorage.setItem("auth_token", response.data.token);
                axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("auth_token");
                toastr["success"](response.data.message);
                _this.$router.push("/inicio");
                return true;
            })
            .catch((error) => {
                toastr["error"](error.response.data.message);
                return false;
            });

        return false
    },

    async logout(_this) {
        return await axios.post('api/auth/logout').then(response => {
            localStorage.removeItem('token_linkedin');
            localStorage.removeItem('token_laravel');
            axios.defaults.headers.common['Authorization'] = null;
            _this.$store.dispatch('resetAuthUserDetail');
            return true;
        }).catch(error => {
            console.log(error);
            return false;
        });
    },

    check() {
        return axios.post('api/auth/check').then(response => {
            if (response.data.Unauthenticated == false) {
                return true;
            }
            return false;
        }).catch(error => {
            console.log(error);
            return false;
        });
    },

    authUser() {
        return axios.get('api/auth/user').then(response => {
            return response.data;
        }).catch(error => {
            console.log(error);
            return error.response.data;
        });
    },



}