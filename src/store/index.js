export default new Vuex.Store({
    state: {
        isConnected: false,
        message: '',
    },
    mutations: {
        SOCKET_ONOPEN (state, event)  {
            state.socket.isConnected = true
        },
        SOCKET_ONCLOSE (state, event)  {
            state.socket.isConnected = false
        },
        SOCKET_ONERROR (state, event)  {
            console.error(state, event)
        },
        // default handler called for all methods
        SOCKET_ONMESSAGE (state, message)  {
            state.message = message
        }
    }
})