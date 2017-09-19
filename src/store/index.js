import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isConnected: false,
        message: '',
        parameterData: [],
        light: '00000000',
        d_num: 0,
        myChartses: [],
        id: 1,
        adcTitle: [
            ['adc1', 'adc2', 's0_p0_re', 's0_p1_re', 's1_p0_re', 's1_p1_re', 's2_p0_re', 's2_p1_re', 's3_p0_re', 's3_p1_re'],
            ['adc1', 'adc2', 'i_0', 'i_4', 'q_0', 'q_4', 'u_0', 'u_4', 'v_0', 'v_4'],
            ['adc1', 'adc2', 'p0_re', 'p0_rm'],
        ]
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
            if(message['id']!=state.id){
                return;
            }
            switch (message['type']) {
                case 'getParameter':
                    state.parameterData = message['parameter'];
                    break;
                case 'light':
                    state.light = message['light'].toString(2);
                    state.d_num = message['num'];
                    break;
                case 'adc':
                    var xData = [];
                    var yData = [];
                    for (var i in message.data) {
                        xData.push(i);
                        yData.push(message.data[i]);
                    }
                    console.log(xData);
                    console.log(yData);


                    state.myChartses[message['no']].setOption({
                        title: {
                            text: message['no']
                        },
                        tooltip: {
                            trigger: 'axis',
                            formatter: function (params) {
                                params = params[0];
                                return params.value;
                            },
                            axisPointer: {
                                animation: false
                            }
                        },
                        xAxis: {
                            data: xData
                        },
                        yAxis: {},
                        series: [{
                            name: '模拟数据',
                            type: 'line',
                            showSymbol: false,
                            hoverAnimation: false,
                            data: yData
                        }]
                    });
                default:
                    break;
            }
        },

        setCharts(state, chart){
            state.myChartses.push(chart);
        },

        setId(state, id){
            state.id = id;
        },
    }
})