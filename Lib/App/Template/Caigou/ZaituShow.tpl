<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在途数</title>
</head>
<link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
</head>
<body>
    <div class="container">
    <h3>在途数——即将到货，敬请关注</h3>
      <table id="table_main" class="table table-hover table-bordered">
          <thead>
            <tr>
                <th>花型六位号</th>
                <th>产品名称</th>
                <th>成分</th>
                <th>纱支</th>
                <th>经纬密</th>
                <th>门幅</th>
                <th>在途数</th>
                <th>单位</th>
                <th>预计到货时间</th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$row item=item}
            <tr>
                <td>{$item.proCode|default:'&nbsp;'}</td>
                <td>{$item.proName|default:'&nbsp;'}</td>
                <td>{$item.chengfen|default:'&nbsp;'}</td>
                <td>{$item.shazhi|default:'&nbsp;'}</td>
                <td>{$item.jingmi}*{$item.weimi}</td>
                <td>{$item.menfu|default:'&nbsp;'}</td>
                <td>{$item.cnt|default:'&nbsp;'}</td>
                <td>{$item.unit|default:'&nbsp;'}</td>
                <td>{$item.jiaoqi|default:'&nbsp;'}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
 </div>
</body>
</html>
