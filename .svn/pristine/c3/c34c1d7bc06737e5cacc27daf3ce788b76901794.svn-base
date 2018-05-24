/*****************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :select_input.js
*  Time   :2014/09/25 14:58:22
*  Remark :固定表头
\*********************************************************************/
(function($) {
	

	var TableHeader = function (el, options) {
		this.$el = $(el);
        // this.$el_ = this.$el.clone();

        this.init();
    };

    TableHeader.prototype.init = function () {
        this.initHeader();
    };

    TableHeader.prototype.initHeader = function () {
        var that = this;

        var thread = $('thead',this.$el);
        var timeOut;
        var _h=0;
        this.$el.parents('.panel-body').scroll(function(){
            var _this = this;
            //如果滚动条左右，不做处理
            if(_h!=_this.scrollTop){
                _h=_this.scrollTop;
            }else{
                return;
            }

            $('th',thread).css({'position':'relative','z-index':100,'background':'#ffffff'});

            clearTimeout(timeOut);

            $('th',thread).css({'top':'0px'});
            
            timeOut = setTimeout(function(){
                $('th',thread).css({'top':_this.scrollTop+'px'}).fadeIn('slow');
            },200);
            
        });
    };

    /**
	 * 默认参数
	 * @var Array
	*/
	TableHeader.defaults = {};

	$.fn.tableHeader = function (option, _relatedTarget) {
		var value;
	    this.each(function () {
            var $this = $(this),
                data = $this.data('table.header'),
                options = $.extend({}, TableHeader.defaults, $this.data(), typeof option === 'object' && option);

            if (!data) {
                $this.data('table.header', (data = new TableHeader(this, options)));
            }

            if (typeof option === 'string') {
                if ($.inArray(option, allowedMethods) < 0) {
                    throw "Unknown method: " + option;
                }
                value = data[option](_relatedTarget);

                if (option === 'destroy') {
                    $this.removeData('table.header');
                }
            }
        });

	    return typeof value === 'undefined' ? this : value; 
	}

})(jQuery);