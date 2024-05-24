import CryptoJS from "crypto-js";
var key = 'Lu15.v1ll3g@$.Hu4t@d0';

export default {
    encrypt(valor) {
        return (CryptoJS.AES.encrypt(valor.toString(), key)).toString();
    },

    decrypt(valor) {
        var bytes = CryptoJS.AES.decrypt(valor, key);
        return bytes.toString(CryptoJS.enc.Utf8);
    },
}