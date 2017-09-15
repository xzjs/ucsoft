import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isConnected: false,
        message: '',
        parameterData: [],
        light:'00000000',
        d_num:0
    },
    mutations: {
        SOCKET_ONOPEN (state, event)  {
            state.isConnected = true
        },
        SOCKET_ONCLOSE (state, event)  {
            state.isConnected = false
        },
        SOCKET_ONERROR (state, event)  {
            console.error(state, event)
        },
        // default handler called for all methods
        SOCKET_ONMESSAGE (state, message)  {
            state.message = message;
            console.log(message);
            switch (message['type']){
                case 'getParameter':
                    state.parameterData=message['parameter'];
                    break;
                case 'light':
                    state.light=message['light'].toString(2);
                    state.d_num=message['num'];
                default:
                    break;
            }
        }
    }
})