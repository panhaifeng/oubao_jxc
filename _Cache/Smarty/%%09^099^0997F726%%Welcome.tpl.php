<?php /* Smarty version 2.6.10, created on 2017-03-19 16:34:06
         compiled from Welcome.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Welcome.tpl', 3, false),array('modifier', 'date_format', 'Welcome.tpl', 159, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%09^099^0997F726%%Welcome.tpl.inc'] = '207ed95c8db80e335fab6f73e9709d16'; ?><html>
<head>
  <title><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:207ed95c8db80e335fab6f73e9709d16#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemName'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:207ed95c8db80e335fab6f73e9709d16#0}';}?>
</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="Resource/Css/scrollbar.css">
  <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:207ed95c8db80e335fab6f73e9709d16#1}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/jquery.1.9.1.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:207ed95c8db80e335fab6f73e9709d16#1}';}?>

  <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:207ed95c8db80e335fab6f73e9709d16#2}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/layer/layer.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:207ed95c8db80e335fab6f73e9709d16#2}';}?>

   <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:207ed95c8db80e335fab6f73e9709d16#3}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/chart/esl.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:207ed95c8db80e335fab6f73e9709d16#3}';}?>

  <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:207ed95c8db80e335fab6f73e9709d16#4}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/Calendar/WdatePicker.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:207ed95c8db80e335fab6f73e9709d16#4}';}?>

</head>
<script type="text/javascript">
var arr = <?php echo $this->_tpl_vars['arr']; ?>
;

<?php echo '
  $(function(){
    $(\'.openView\').click(function(){
      var title=$(this).attr(\'title\')||"查看信息";
      var url=$(this).attr(\'url\');
      $.layer({
          type: 2,
          shade: [0],
          fix: false,
          title: title,
          maxmin: true,
          iframe: {src : url},
          // border:false,
          area: [\'720px\' , \'440px\'],
          close: function(index){//关闭时触发
             
          },
          //回调函数定义
          callback:function(index,ret) {
            
          }
        });
      });
       // 路径配置
    require.config({
        paths:{ 
            \'echarts\' : \'Resource/Script/chart/echarts\',
            \'echarts/chart/bar\' : \'Resource/Script/chart/echarts-map\',
            \'echarts/chart/line\': \'Resource/Script/chart/echarts-map\',
            \'echarts/chart/funnel\': \'Resource/Script/chart/echarts-map\',
            \'echarts/chart/map\': \'Resource/Script/chart/echarts-map\'
        }
    });

    require(
      [
        \'echarts\',
        \'echarts/chart/line\',   // 按需加载所需图表
        \'echarts/chart/bar\',
        \'echarts/chart/pie\',
        \'echarts/chart/funnel\',
        \'echarts/chart/map\'
      ], function (ec) {
        // 客户来源分析图表
        var myChart = ec.init(document.getElementById(\'map_level\'));
         var option_map = {
            title : {
                text: \'下单个数和下单米数\',
                // subtext: \'纯属虚构\'
            },
            tooltip : {
                trigger: \'axis\'
            },
            legend: {
                data:[\'下单个数\',\'下单米数\']
            },
            // toolbox: {
            //     show : true,
            //     feature : {
            //         mark : {show: true},
            //         dataView : {show: true, readOnly: false},
            //         magicType : {show: true, type: [\'line\', \'bar\']},
            //         restore : {show: true},
            //         saveAsImage : {show: true}
            //     }
            // },
            calculable : true,
            xAxis : [
                {
                    type : \'category\',
                    data : [\'1月\',\'2月\',\'3月\',\'4月\',\'5月\',\'6月\',\'7月\',\'8月\',\'9月\',\'10月\',\'11月\',\'12月\']
                }
            ],
            yAxis : [
                {
                    type : \'value\'
                }
            ],
            series : [
                {
                    name:\'下单个数\',
                    type:\'bar\',
                    data:[arr[1].num,arr[2].num, arr[3].num, arr[4].num, arr[5].num, arr[6].num, arr[7].num, arr[8].num,arr[9].num, arr[10].num,arr[11].num, arr[12].num],
                    markPoint : {
                        data : [
                            {type : \'max\', name: \'最大值\'},
                            {type : \'min\', name: \'最小值\'}
                        ]
                    },
                    // markLine : {
                    //     data : [
                    //         {type : \'average\', name: \'平均值\'}
                    //     ]
                    // }
                },
                {
                    name:\'下单米数\',
                    type:\'bar\',
                    data:[arr[1].cntM,arr[2].cntM, arr[3].cntM, arr[4].cntM, arr[5].cntM, arr[6].cntM, arr[7].cntM, arr[8].cntM,arr[9].cntM, arr[10].cntM,arr[11].cntM, arr[12].cntM],
                    // markPoint : {
                    //     data : [
                    //         {name : \'年最高\', value : 182.2, xAxis: 7, yAxis: 183, symbolSize:18},
                    //         {name : \'年最低\', value : 2.3, xAxis: 11, yAxis: 3}
                    //     ]
                    // },
                    // markLine : {
                    //     data : [
                    //         {type : \'average\', name : \'平均值\'}
                    //     ]
                    // }
                }
            ]
        };
        myChart.setOption(option_map);

       
  });

  });
</script>
<style type="text/css">
.main{
    height: 400px;
    overflow: auto;
    border: 0px;
}
body{
  margin-left:5px; margin-top:5px; margin-right: 8px;
}
.list-group-item{
  border-radius: 0px !important;
}
.panel-body{
  padding: 0px 15px 0 15px;
}
</style>
'; ?>

<body>
<div class="container-fluid">
    <div class="row">
    <!-- 订单图表 -->
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="text-align:left;"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y') : smarty_modifier_date_format($_tmp, '%Y')); ?>
年下单分析</h3>
                </div>
                <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="main" id="map_level">
                            </div>
                        </div>
                </div>
            </div>
        </div>
          <!-- 消息 -->
        <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title" style="text-align:left;">消息提醒<small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;今天是<?php echo $this->_tpl_vars['dateTo']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;星期<?php echo $this->_tpl_vars['today']; ?>
</small></h3>
              </div>
              <div class="panel-body tixing_message">
                <div class="row">
                  <ul class="list-group">
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col-xs-12">订单：</div>
                      </div>
                      <div class="row">
                        <div class="col-xs-6">
                          <a href='?controller=Trade_Order&action=NewAdd&no_edit=1' target="_blank">
                            本周新增订单<font color="red">(<?php echo $this->_tpl_vars['order']; ?>
)</font>
                          </a>
                        </div>
                        <div class="col-xs-6">
                          <a href='?controller=Trade_Order&action=FahuoWaiting&no_edit=1&fahuo=1' target="_blank">
                            待确认发货单<font color="red">(<?php echo $this->_tpl_vars['fahuoWaiting']; ?>
)</font>
                          </a>
                        </div>
                        <div class="col-xs-6">
                          <a href='?controller=Trade_Order&action=HtWaiting&no_edit=1' target="_blank">
                            未出库销售合同<font color="red">(<?php echo $this->_tpl_vars['fahuo']; ?>
)</font>
                          </a>
                        </div>
                        <div class="col-xs-6">
                          <a href='?controller=Caigou_Order&action=WillJiaoqi&no_edit=1' target="_blank">
                            产品即将交期<font color="red">(<?php echo $this->_tpl_vars['jiaoqi']; ?>
)</font>
                          </a>
                        </div>
                      </div>

                    </li>

                    <li class="list-group-item">
                      <div class="row">
                        <div class="col-xs-12">出库单：</div>
                      </div>
                      <div class="row">
                        <div class="col-xs-6">
                          <a href='?controller=Cangku_Plan&action=Jiaoyan&no_edit=1' target="_blank">
                            待校验出库单<font color="red">(<?php echo $this->_tpl_vars['jiaoyan']; ?>
)</font>
                          </a>
                        </div>
                        <div class="col-xs-6">
                          <a href='?controller=Cangku_Chuku&action=ChukuWaiting&no_edit=1' target="_blank">
                            未完成出库单<font color="red">(<?php echo $this->_tpl_vars['chuku']; ?>
)</font>
                          </a>
                        </div>
                      </div>
                    </li>

                    <li class="list-group-item">
                      <div class="row">
                        <div class="col-xs-12">待审核：</div>
                      </div>
                      <div class="row">
                        <div class="col-xs-6">
                          <a href='?controller=Caigou_Order&action=ShenheWaiting&no_edit=1' target="_blank">
                            采购合同待审核<font color="red">(<?php echo $this->_tpl_vars['caigou']; ?>
)</font>
                          </a>
                        </div>
                        <div class="col-xs-6">
                          <a href='?controller=Trade_Order&action=HetongSh&no_edit=1&shenhe=1' target="_blank">
                            销售合同待审核<font color="red">(<?php echo $this->_tpl_vars['xiaoshou']; ?>
)</font>
                          </a>
                        </div>
                        <div class="col-xs-6">
                          <a href='?controller=Caigou_Shengou&action=ShenheWaiting&no_edit=1' target="_blank">
                            申购单待审核<font color="red">(<?php echo $this->_tpl_vars['shengou']; ?>
)</font>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col-xs-12">会员：</div>
                      </div>
                      <div class="row">
                        <div class="col-xs-6">
                          <a href='?controller=Jichu_Client&action=NewClient&no_edit=1&fahuo=1' target="_blank">
                            本周新增会员<font color="red">(<?php echo $this->_tpl_vars['huiyuan']; ?>
)</font>
                          </a>
                        </div>
                      </div>
                    </li>

                  </ul>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

    <!-- </div> -->
</body>
</html>