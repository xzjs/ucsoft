<template>
    <div>
        <el-row type="flex" class="row-bg" justify="end">
            <el-col :span="12">
                <img src="../assets/zdhs.jpg" alt="自动化所">
            </el-col>
        </el-row>
        <el-row type="flex" justify="center">
            <el-col>
                <h1>{{name[id - 1]}}</h1>
            </el-col>
        </el-row>
        <el-row :gutter="20" :span="24">
            <el-col :span="12">
                <el-row type="flex" class="row-bg" justify="space-around" :gutter="5">
                    <el-col :span="6">
                        <el-select v-model="ppsValue" placeholder="1PPS">
                            <el-option label="internal" value="internal"></el-option>
                            <el-option label="external" value="external"></el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="6">
                        <el-button type="primary" @click="start">下位机启动</el-button>
                    </el-col>
                    <el-col :span="6">
                        <el-button type="primary" @click="rest">软复位</el-button>
                    </el-col>
                    <el-col :span="6">
                        <span>万兆网</span>
                        <el-switch
                                v-model="networkValue"
                                @change="networkCtrl"
                                on-color="#13ce66"
                                off-color="#ff4949">
                        </el-switch>
                    </el-col>
                </el-row>
                <el-row>
                    <el-row>
                        <h3>参数配置类型Ⅰ</h3>
                    </el-row>
                    <el-row type="flex" class="row-bg" :gutter="5">
                        <el-col :span="6">
                            <el-select v-model="bitValue" placeholder="参数位宽">
                                <el-option label="8bits" value="8"></el-option>
                                <el-option label="16bits" value="16"></el-option>
                                <el-option label="32bits" value="32"></el-option>
                            </el-select>
                        </el-col>
                        <el-col :span="6">
                            <el-button type="primary">参数组别</el-button>
                        </el-col>
                        <el-col :span="6">
                            <el-button type="primary">保存</el-button>
                        </el-col>
                        <el-col :span="6">
                            <el-button type="primary">下载</el-button>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-input
                                type="textarea"
                                autosize
                                :autosize="{ minRows: 5, maxRows: 100}"
                                placeholder="参数显示与编辑"
                                v-model="parameterValue">
                        </el-input>
                    </el-row>
                </el-row>
                <el-row>
                    <el-row>
                        <h3>参数配置类型Ⅱ</h3>
                    </el-row>
                    <el-row type="flex" class="row-bg" :gutter="5">
                        <el-col :span="6">
                            <el-select v-model="bitValue" placeholder="参数位宽">
                                <el-option label="8bits" value="8"></el-option>
                                <el-option label="16bits" value="16"></el-option>
                                <el-option label="32bits" value="32"></el-option>
                            </el-select>
                        </el-col>
                        <el-col :span="6">
                            <el-select v-model="group2Value" placeholder="参数组别" @change="get2Parameters">
                                <el-option label="ten_gbe_param" value="ten_gbe_param"></el-option>
                                <el-option label="adc_cal_param" value="adc_cal_param"></el-option>
                                <el-option label="app_param" value="app_param"></el-option>
                            </el-select>
                        </el-col>
                        <el-col :span="6">
                            <el-button type="primary" @click="set2Parameters">保存</el-button>
                        </el-col>
                        <el-col :span="6">
                            <el-button type="primary" @click="download">下载</el-button>
                        </el-col>
                    </el-row>
                    <el-row>
                        <ul>
                            <li v-for="(item,index) in parameterData">
                                <el-col :span="12">
                                    <span>{{group2Parameters[id - 1][group2Value][index]}}:</span>
                                </el-col>
                                <el-col :span="12">
                                    <span>0x</span>
                                    <el-input class="parameter_input" v-model="parameterData[index]"
                                              placeholder="请输入内容"></el-input>
                                </el-col>
                            </li>
                        </ul>
                    </el-row>
                </el-row>
            </el-col>
            <el-col :span="12">
                <el-row>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[0]=='1'}"></i>
                        <p>adc</p>
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[1]=='1'}"></i>
                        <p class="light_word">ten_gbeparam_done</p>
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[2]=='1'}"></i>
                        <p class="light_word">app_param_done</p>
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[3]=='1'}"></i>
                        <p class="light_word">ten_gbe_link</p>
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[4]=='1'}"></i>
                        <br/>

                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[5]=='1'}"></i>
                        <br/>

                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[6]=='1'}"></i>
                        <br/>

                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check" v-bind:class="{light:light[7]=='1'}"></i>
                        <br/>

                    </el-col>
                </el-row>
                <el-row :gutter="20" type="flex" class="row-bg" justify="space-around">
                    <el-col :span="8">
                        <el-button type="primary">温度</el-button>
                    </el-col>
                    <el-col :span="8">
                        <el-button type="primary">电压</el-button>
                    </el-col>
                    <el-col :span="8">
                        <el-button type="primary">电流</el-button>
                    </el-col>
                </el-row>
                <el-row :gutter="20" type="flex" class="row-bg" justify="space-around">
                    <el-col :span="12">
                        <p>上位机发送的包个数：{{upNum}}</p>
                    </el-col>
                    <el-col :span="12">
                        <p>下位机发送的包个数：{{d_num}}</p>
                    </el-col>
                </el-row>
                <el-row>
                    <div class="adc" id="adc1"></div>
                    <div class="adc" id="adc2"></div>
                    <div class="adc" id="adc3"></div>
                    <div class="adc" id="adc4"></div>
                    <div class="adc" id="adc5"></div>
                    <div class="adc" id="adc6"></div>
                    <div class="adc" id="adc7"></div>
                    <div class="adc" id="adc8"></div>
                    <div class="adc" id="adc9"></div>
                    <div class="adc" id="adc10"></div>
                </el-row>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import ElButton from "../../node_modules/element-ui/packages/button/src/button";

    export default {
        components: {ElButton},
        data () {
            return {
                name: ['narrow bandwidth spectral line backend', 'pulsar search backend', 'baseband data backend'],
                ppsValue: 'internal',
                startValue: true,
                networkValue: false,
                bitValue: '',
                parameterValue: '',
                myChartses: [],
                now: '',
                oneDay: '',
                value: '',
                upNum: 0,
                id: 1,
                group2Value: '',
                group2Parameters: [
                    {
                        "ten_gbe_param": ['标志位',
                            "src_mac_addr_l16", "src_mac_addr_m16", 'src_mac_addr_h16', 'src_ip_addr_l16',
                            "src_ip_addr_h16", "src_port_number", 'dest_mac_addr_l16', 'dest_mac_addr_m16',
                            "dest_mac_addr_h16", "dest_ip_addr_l16", 'dest_ip_addr_m16', 'dest_port_number',
                            "pkt_len_l16", "pkt_len_h16"
                        ],
                        "adc_cal_param": ['标志位',
                            "adc1_offset_A", "adc1_offset_B", 'adc1_offset_C', 'adc1_offset_D',
                            "adc1_gain_A", "adc1_gain_B", 'adc1_gain_C', 'adc1_gain_D',
                            "adc1_phase_A", "adc1_phase_B", 'adc1_phase_C', 'adc1_phase_D',
                            "adc2_offset_A", "adc2_offset_B", 'adc2_offset_C', 'adc2_offset_D',
                            "adc2_gain_A", "adc2_gain_B", 'adc2_gain_C', 'adc2_gain_D',
                            "adc2_phase_A", "adc2_phase_B", 'adc2_phase_C', 'adc2_phase_D',
                        ],
                        "app_param": ['标志位',
                            "mode_sel", "band_sel",
                            's0_gain', 's1_gain', "s2_gain", "s3_gain", 's4_gain', 's5_gain', "s6_gain", "s7_gain",
                            's0_mixer_cnt', 's1_mixer_cnt', "s2_mixer_cnt", "s3_mixer_cnt", 's4_mixer_cnt', 's5_mixer_cnt', "s6_mixer_cnt", "s7_mixer_cnt",
                            'sync_period', 'ssg_length', "ssg_sel", "adc_sync_sel"
                        ]
                    }
                ]
            }
        },
        computed: {
            ...mapState(['parameterData', 'light', 'd_num'])
        },
        methods: {
            randomData(){
                this.now = new Date(+this.now + this.oneDay);
                this.value = this.value + Math.random() * 21 - 10;
                return {
                    name: this.now.toString(),
                    value: [
                        [this.now.getFullYear(), this.now.getMonth() + 1, this.now.getDate()].join('/'),
                        Math.round(this.value)
                    ]
                }
            },
            getId(){
                if (this.$route.params.id != null) {
                    if (this.$route.params.id > 3) {
                        this.id = 1;
                    }
                    this.id = this.$route.params.id;
                } else {
                    this.id = 1;
                }
            },
            /**
             * 获取参数
             * @param groupName
             */
            get2Parameters(groupName){
                let data = {'id': this.id, 'group': groupName, 'type': 'getParameter'};
                this.$socket.sendObj(data);
                this.upNum += 1;
            },
            /**
             * 设置参数
             */
            set2Parameters(){
                let data = {
                    id: this.id,
                    group: this.group2Value,
                    type: 'setParameter',
                    parameters: this.parameterData
                };
                this.$socket.sendObj(data);
                this.upNum += 1;
            },
            /**
             * 下发参数
             */
            download(){
                let data = {
                    id: this.id,
                    group: this.group2Value,
                    type: 'downloadParameter'
                };
                this.$socket.sendObj(data);
                this.upNum += 1;
            },
            /**
             * 万兆网开关
             */
            networkCtrl(value){
                let data = {
                    id: this.id,
                    type: 'networkCtrl',
                    value: value
                };
                this.$socket.sendObj(data);
                this.upNum += 1;
            },
            /**
             * 软复位
             */
            rest(){
                let data = {
                    id: this.id,
                    type: 'rest'
                };
                this.$socket.sendObj(data);
                this.upNum += 1;
            },
            /**
             * 下位机启动
             */
            start(){
                let data = {
                    id: this.id,
                    type: 'start',
                    pps: this.ppsValue
                };
                this.$socket.sendObj(data);
                this.upNum += 1;
            }
        },
        mounted(){
            this.getId();

            let echarts = require('echarts');
            this.now = +new Date(1997, 9, 3);
            this.oneDay = 24 * 3600 * 1000;
            this.value = Math.random() * 1000;
            for (var i = 1; i <= 10; i++) {
                let myCharts = echarts.init(document.getElementById('adc' + i));
                var data = [];
                for (var j = 0; j < 1000; j++) {
                    data.push(this.randomData());
                }
                var option = {
                    title: {
                        text: i
                    },
                    tooltip: {
                        trigger: 'axis',
                        formatter: function (params) {
                            params = params[0];
                            var date = new Date(params.name);
                            return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
                        },
                        axisPointer: {
                            animation: false
                        }
                    },
                    xAxis: {
                        type: 'time',
                        splitLine: {
                            show: false
                        }
                    },
                    yAxis: {
                        type: 'value',
                        boundaryGap: [0, '100%'],
                        splitLine: {
                            show: false
                        }
                    },
                    series: [{
                        name: '模拟数据',
                        type: 'line',
                        showSymbol: false,
                        hoverAnimation: false,
                        data: data
                    }]
                };
                myCharts.setOption(option);
                this.myChartses.push(myCharts);
            }
        },
        watch: {
            '$route' (to, from) {
                this.getId();
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .el-button {
        width: 100%;
    }

    .light {
        color: #13ce66;
    }

    .adc {
        width: 200px;
        height: 200px;
        float: left;
    }

    .light_word {
        word-break: break-all;
    }

    ul {
        list-style: none;
    }

    .parameter_input {
        width: 100px;
    }
</style>
