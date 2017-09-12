<template>
    <div>
        <el-row type="flex" justify="center">
            <el-col :span="8">
                <h1>{{name}}</h1>
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
                        下位机启动
                        <el-switch
                                v-model="startValue"
                                on-color="#13ce66"
                                off-color="#ff4949">
                        </el-switch>
                    </el-col>
                    <el-col :span="6">
                        <el-button type="primary">软复位</el-button>
                    </el-col>
                    <el-col :span="6">
                        万兆网开关
                        <el-switch
                                v-model="networkValue"
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
                        <el-col :span="6"><el-button type="primary">参数组别</el-button></el-col>
                        <el-col :span="6"><el-button type="primary">保存</el-button></el-col>
                        <el-col :span="6"><el-button type="primary">下载</el-button></el-col>
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
                        <el-col :span="6"><el-button type="primary">参数组别</el-button></el-col>
                        <el-col :span="6"><el-button type="primary">保存</el-button></el-col>
                        <el-col :span="6"><el-button type="primary">下载</el-button></el-col>
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
            </el-col>
            <el-col :span="12">
                <el-row>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
                        <br/>
                        adc
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
                        <br/>
                        ten_gbeparam_done
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
                        <br/>
                        app_param_done
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
                        <br/>
                        ten_gbe_link
                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
                        <br/>

                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
                        <br/>

                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
                        <br/>

                    </el-col>
                    <el-col :span="3">
                        <i class="el-icon-circle-check"></i>
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
    import ElRow from "element-ui/packages/row/src/row";
    export default {
        components: {ElRow},
        data () {
            return {
                name:'下位机应用',
                ppsValue:'',
                startValue:true,
                networkValue:true,
                bitValue:'',
                parameterValue:'',
                myChartses:[],
                now:'',
                oneDay:'',
                value:''
            }
        },
        methods:{
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
            }
        },
        mounted(){
            let echarts = require('echarts');
            this.now = +new Date(1997, 9, 3);
            this.oneDay = 24 * 3600 * 1000;
            this.value = Math.random() * 1000;
            for(var i=1;i<=10;i++){
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
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .el-button{
        width: 100%;
    }
    .el-icon-circle-check{
        color: #13ce66;
    }
    .adc{
        width: 200px;
        height: 200px;
        float: left;
    }
</style>
