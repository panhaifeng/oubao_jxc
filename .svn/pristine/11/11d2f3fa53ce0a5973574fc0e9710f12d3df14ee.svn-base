<div id='searchGuide'  class='x-hide-display'>
	<form name="FormSearch" id='FormSearch' method="post" action="" class="form-horizontal">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
        <div id="search_height">
        <span id="search_img" class="glyphicon glyphicon-zoom-in"></span>
        <!-- <a href="#" id="search_img"><img src="Resource/Image/search.png" ext:qtip="搜索"></a> -->
        </div>
        <div id="search_input_div">
		{foreach from=$arr_condition item=item key=key}
			{assign var="f" value="Lib/App/Template/Search/"|cat:$key|cat:".tpl"}
			{if file_exists($f)}
			<tt>{include file="Search/"|cat:$key|cat:".tpl"}</tt>
			{/if}
		{/foreach}
		<tt>
			<button type="submit" class="btn btn-info button_search btn-xs">
	            <span class="glyphicon glyphicon-search"></span> 搜索
	        </button>
        </tt>
        </div>
		</td>
		{if $smarty.get.no_edit!=1}
		<td id="edit" align="right" style="padding-right:10px;">
			{if $add_display!='none'}
			<a href="{if $add_url==''}{url controller=$smarty.get.controller action='add'}
			{else}{$add_url}
			{/if}"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;{$add_text|default:'新增'}</a>
			{/if}
            {if $other_url!=''}{$other_url}{/if}
			{if $list_display=='list'||$list_url!=''}
			<a href="{if $list_url==''}{url controller=$smarty.get.controller action='list'}
			{else}{$list_url}
			{/if}">{$list_text|default:'查询'}</a>
			{/if}
		</td>
		{/if}
      </tr>
    </table>
	</form>
</div>