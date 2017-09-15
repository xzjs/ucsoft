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
        myChartses: []
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
                    for(var i in message.data){
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
        }
    }
})