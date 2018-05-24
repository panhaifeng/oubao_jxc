<html>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">
<head>
  <title>{webcontrol type='GetAppInf' varName='systemName'}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  {webcontrol type='LoadJsCss' src="Resource/Css/SearchItemTpl.css"}
  {webcontrol type='LoadJsCss' src="Resource/bootstrap/css/bootstrap.css"}
  {webcontrol type='LoadJsCss' src="Resource/Script/chart/esl.js"}
  {webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
  {webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
</head>

<script type="text/javascript">
var name_kind = {$name};//花型等级
var money = {$money};//花型销
var _controllerName = '{$_controllerName}';//控制器名
{literal}
  $(function(){
    $('#shouDetails').click(function(){
      var url="?controller="+_controllerName+"&action=Right";
      var dateFrom = $('#dateFrom').val();
      var dateTo = $('#dateTo').val();
      if(dateFrom!='')url+="&dateFrom="+dateFrom;
      if(dateTo!='')url+="&dateTo="+dateTo;
      window.location.href=url;
    });
  });
    // 路径配置
    require.config({
        paths:{ 
            'echarts' : 'Resource/Script/chart/echarts',
            'echarts/chart/bar' : 'Resource/Script/chart/echarts-map',
            'echarts/chart/line': 'Resource/Script/chart/echarts-map',
            'echarts/chart/funnel': 'Resource/Script/chart/echarts-map',
            'echarts/chart/map': 'Resource/Script/chart/echarts-map'
        }
    });

    require(
      [
        'echarts',
        'echarts/chart/line',   // 按需加载所需图表
        'echarts/chart/bar',
        'echarts/chart/pie',
        'echarts/chart/funnel',
        'echarts/chart/map'
      ], function (ec) {
        // 客户来源分析图表
        var myChart = ec.init(document.getElementById('map_level'));
         var option_map =  {
            title : {
                text: '花型大类分析图表',
                subtext: '',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                //data:['申请人数','实到人数']
                data:name_kind
            },
            toolbox: {
              show : true,
              feature : {
                    restore : {show: true},
                    saveAsImage : {show: true},
                    dataView : {show: true, readOnly: false},
              }
            },
            calculable : true,
            series : [
                {
                    name:'花型大类',
                    type:'pie',
                    radius : '70%',
                    center: ['50%', '50%'],
                    data:money
                }
            ]
        };
        myChart.setOption(option_map);

       
  });
    
function changeDate(obj){
  var df = document.getElementById('dateFrom');
  var dt = document.getElementById('dateTo');
  var d=new Date();
  var year=d.getFullYear();
  var m=parseInt(obj.value)+1;
  if(m<10) m="0"+m;
  df.value=year+'-'+m+'-'+'01';
  //如果为1、3、5、7、8、10、12一个月为31天
  if(obj.value=='0'||obj.value=='2'||obj.value=='4'||obj.value=='6'||obj.value=='7'||obj.value=='9'||obj.value=='11')
  {

    dt.value=year+'-'+m+'-'+'31';
  }
  //如果是2月份判断是否闰年
  if(obj.value=='1') {
    if((year%4==0 && year%100!=0) || year%400==0){
      dt.value=year+'-'+m+'-'+'29';
    }else{
      dt.value=year+'-'+m+'-'+'28';;
    }
  }
  if(obj.value=='13') {
    df.value='{/literal}{php}echo date("2010-01-01");{/php}{literal}';
    dt.value='{/literal}{php}echo date("Y-m-d",mktime(0,0,0,1,0,date("Y")+1));{/php}{literal}';
  }
  if(obj.value=='3'||obj.value=='5'||obj.value=='8'||obj.value=='10')
  {
    dt.value=year+'-'+m+'-'+'30';
  }

}
function form_submit(){
  document.getElementById("FormSearch").submit();
}
</script>

<style type="text/css">
body{
  position: relative;
  padding: 0px 0px 0px 0px;
  margin: 0px 0px 0px 0px;
}
body::-webkit-scrollbar {
  -webkit-appearance: none;
  background-color: rgba(0,0,0, .10);
  width: 8px;
  height: 8px;
}

body::-webkit-scrollbar-thumb {
  border-radius: 4;
  background-color: rgba(0,0,0, .4);
}

#searchGuide{
  height: 32px;
  /*margin-top: -1px;*/
  margin-bottom: 2px;
}
.main{
  min-height: 450px;
}
select{
  font-family:  verdana,arial,helvetica,sans-serif !important;
}
#shouDetails{
  width: 150px;
}
</style>
{/literal}
<body>
  <div class="container-fluid">
  {if $smarty.get.no_edit!=1}{include file="_Search.tpl"}{/if}
  <!-- 面板 -->
  <div class="row">
    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="main" id="map_level">
        </div>
      </div>
    </div>
  </div>
  </div>
</body>
</html>