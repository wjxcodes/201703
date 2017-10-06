/* 弹窗js */
function show(){
    $(".dianji").css({"height=100%":window.screen.availHeight});
    $(".dianji").show();
 
    var st=$(document).scrollTop(); //页面滑动高度
    var objH=$(".yincang").height();//浮动对象的高度
    var ch=$(window).height();//屏幕的高度  
    var objT=Number(st)+(Number(ch)-Number(objH))/2;   //思路  浮动高度+（（屏幕高度-对象高度））/2
    $(".yincang").css("top",objT);
     
    var sl=$(document).scrollLeft(); //页面滑动左移宽度
    var objW=$(".yincang").width();//浮动对象的宽度
    var cw=$(window).width();//屏幕的宽度  
    var objL=Number(sl)+(Number(cw)-Number(objW))/2; //思路  左移浮动宽度+（（屏幕宽度-对象宽度））/2
    $(".yincang").css("left",objL);
    $(".yincang").show();//这里显示方式多种效果
}
function closeDiv(){
    $(".dianji").hide();
    $(".yincang").hide();
    $(".yincang1").hide();
}
/*  滚动条 js */
function autoScroll(obj){  
      $(obj).find("ul").animate({  
        marginTop : "-39px"  
      },500,function(){  
        $(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
      })  
    }  
    $(function(){  
      setInterval('autoScroll(".maquee")',3000);
      setInterval('autoScroll(".apple")',2000);
    }) 
/*  滑动条 js */
$(function() {
        var $document   = $(document);
        var selector    = '[data-rangeslider]';
        var $inputRange = $(selector);
        // Example functionality to demonstrate a value feedback
        // and change the output's value.
        function valueOutput(element) {
            var value = element.value;
            var output = element.parentNode.getElementsByTagName('output')[0];
            output.innerHTML = value;
        }
        // Initial value output
        for (var i = $inputRange.length - 1; i >= 0; i--) {
            valueOutput($inputRange[i]);
        };
        // Update value output
        $document.on('input', selector, function(e) {
            valueOutput(e.target);
        });
        // Initialize the elements
        $inputRange.rangeslider({
            polyfill: false
        });
        // Example functionality to demonstrate programmatic value changes
        $document.on('click', '#js-example-change-value button', function(e) {
            var $inputRange = $('input[type="range"]', e.target.parentNode);
            var value = $('input[type="number"]', e.target.parentNode)[0].value;
            $inputRange
                .val(value)
                .change();
        });
        // Example functionality to demonstrate programmatic attribute changes
        $document.on('click', '#js-example-change-attributes button', function(e) {
            var $inputRange = $('input[type="range"]', e.target.parentNode);
            var attributes = {
                min: $('input[name="min"]', e.target.parentNode)[0].value,
                max: $('input[name="max"]', e.target.parentNode)[0].value,
                step: $('input[name="step"]', e.target.parentNode)[0].value
            };
            $inputRange
                .attr(attributes)
                .rangeslider('update', true);
        });
        // Example functionality to demonstrate destroy functionality
        $document
            .on('click', '#js-example-destroy button[data-behaviour="destroy"]', function(e) {
                $('input[type="range"]', e.target.parentNode).rangeslider('destroy');
            })
            .on('click', '#js-example-destroy button[data-behaviour="initialize"]', function(e) {
                $('input[type="range"]', e.target.parentNode).rangeslider({ polyfill: false });
            });
    });
/*   借款时间切换  */

function time_switch_1(){
    $("#time_14").attr('class','blue');
    $("#time_7").attr('class','white');
  }
 function time_switch_2(){
    $("#time_7").attr('class','blue');
    $("#time_14").attr('class','white');
  }