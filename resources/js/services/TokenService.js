import Vue from "vue";
import VueCookies from 'vue-cookies';
Vue.use(VueCookies)

export default class TokenService {
    buildBearerToken() {
        if(VueCookies.isKey('user')) {
            VueCookies.get('user')
            return "Bearer" + ' ' + VueCookies.get('user')
        }
    }
}
