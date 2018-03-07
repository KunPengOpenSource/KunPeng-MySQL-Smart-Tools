// JavaScript Document
//获取系统当前时间
$(document).ready(function(){
    function current(){ 
        var d=new Date(),str=''; 
        str +=d.getFullYear()<10 ? "0"+d.getFullYear()+'-':d.getFullYear()+'-'; 
        str +=(d.getMonth()+1)<10 ? "0"+(d.getMonth()+1)+'-':(d.getMonth()+1)+'-'; 
        str +=d.getDate()<10 ? "0"+d.getDate()+' ':d.getDate()+' '; 
        str +=d.getHours()<10 ? "0"+d.getHours()+':':d.getHours()+':'; 
        str +=d.getMinutes()<10 ? "0"+d.getMinutes()+':':d.getMinutes()+':'; 
        str +=d.getSeconds()<10 ? "0"+d.getSeconds():d.getSeconds(); 
        return str;
    }
    setInterval(function(){$("#nowTime").html(current);},1000);
//收起展开
    $(".ls").click(function(){
        var str1=$(this).attr("id");
        var len=str1.length;
        var str2=str1.substring(2,len);	
        var str3="#tb"+str2;
        var i=0;
        $(".lf").each(function(){
            i++;
            var str4="#tb"+i;
            if(str3===str4){
                $(str3).slideToggle();
            }else{
                $(str4).slideUp();
                $("#more"+i).text("Click to see more");
            }    
        });
        $(".child").remove();
    });	
    
    var rh = $('.right');
    if(rh.height() < 475){
        rh.css('height','475px');
    }
    
    //edit  
    jQuery(".ok").click(function(){
        var str1=$(this).attr("id");
	var len=str1.length;
        var str2=str1.substring(2,3);
        var str3=str1.substring(3,len);
        var tex="#text"+str3;
        var nid="#vari"+str3;
        var vid="#var"+str3;
        var nidv=$(nid).val();
        var vidv=$(vid).val();
        jQuery.post(funct,{x:nidv,y:vidv,z:str2},function(data){
            $(tex).text(data);
            $(vid).val(data);
            $(".lf").slideUp();
        });   
    });
    
    //表格预览
    jQuery(".ylan").click(function(){
        mask();
        $( '#close' ).click( function () {
            $('#formcontent table #datalist').replaceWith(" ");
            close('#ylanct');
            return false;
	} );
       var id=$(this).attr("id");
       var tid=$("img").attr("id");
       
       jQuery.post(ylan,{id:id,tid:tid},function(data){
            var obj = $( '#ylanct' );
            obj.css( {
                    left : ( $( window ).width() - obj.width() ) / 2,
                    top : $( document ).scrollTop() + ( $( window ).height() - obj.height() ) / 2
            } ).fadeIn();
            if(data.status){
                var strx1=new Array();
                strx1=data.str.split("##");
                lenx1=strx1.length;
                var htm = "";
                for(m=0;m<lenx1;m++){
                    htm+="<tr width='628' id='datalist'>";
                    strx2=strx1[m].split("#");
                    for(n=0;n<strx2.length;n++){
                        htm+="<td width='125'>"+strx2[n]+"</td>";
                    }
                    htm+="</tr>"; 
                }
                $("#formcontent table").append(htm);
            }
       },'json'); 
   });
   //删除提示框
   $(".drop").click(function(){
            strinfo=$(this).attr("title");
            if(strinfo.indexOf("/") >= 0){
                arr = new Array();
                arr = strinfo.split('/');
                dbname = arr[0];
                tbname  = arr[1];
                strurl      =  arr[2];
                prikey     = arr[3];
                prival      =  arr[4];
            }else
                 dbname=strinfo;
            index=$(this).attr("id");
            mask();
            position();
            dialogshow("drop");
            $('.dropdialog').fadeIn('slow');
            if(strinfo.indexOf('/') >= 0){
                if(strurl=="tbdrop")
                        del(tbdrop,{dbname:dbname,tbname:tbname});
                if(strurl=="tbdatadelete")
                        del(tbdatadelete,{dbname:dbname,tbname:tbname,prikey:prikey,prival:prival});
                if(strurl=="tbdropcolumn")
                        del(tbdropcolumn,{dbname:dbname,tbname:tbname,colname:prikey});
            }else
                del(dbdrop,{dbname:dbname});
            $('#close').click(function(){
                close('.dropdialog');
            });            
  });
  $(".delete").click(function(){
            str=$(this).attr("title");
            arr=new Array();
            arr=str.split('/');
            str1=arr[0];
            str2=arr[1];
            str3=arr[2];
            index=$(this).attr('id');
            mask();
            position();
            dialogshow("delete");
            $('.dropdialog').fadeIn('slow');
            del(str3,{str2:str2,str1:str1});
            $('#close').click(function(){
                close('.dropdialog');
            });
  });
  $(".empty").click(function(){
            strinfo=$(this).attr("title");
            arr = new Array();
            arr = strinfo.split('/');
            dbname = arr[0];
            tbname  = arr[1];
            index=$(this).attr("id");
            mask();
            position();
            dialogshow("empty");
            $('.dropdialog').fadeIn('slow');
            del(empty,{dbname:dbname,tbname:tbname});
            $('#close').click(function(){
                close('.dropdialog');
            });            
  });
  //mysql进程中断控制
  $(".kill").click(function(){
            str=$(this).attr("id");
            arr=new Array();
            arr=str.split(' ');
            killid=arr[0];
            index=arr[1];
            state=$(this).attr("title");
            mask();
            position();
            if(state !== ""){
                dialogshow("kill");
                $("#dropcontent p").css('padding',"30px 50px 30px 50px");
                $("#dropcontent p").html("<b>The process state:"+state+",do you really want to kill it?</b>");
            }else{
                dialogshow("kill");
            }
            $('.dropdialog').fadeIn('slow');
            del(kill,{id:killid});
            $('#close').click(function(){
                close('.dropdialog');
            });
  });
  //linux-status left菜单添加当前选中样式
  var url=window.location.pathname;
  var id=url.substr(-1);
  $('#list'+id).parent().addClass('current');
  //slowquery sql语句完全显示 
  $(".unfold").click(function(){
            var str=$(this).attr('id');
            var sql=str.split("/");
            if($(this).text()=="+"){
                $(this).text("-");
                $('#x'+sql[0]).empty();
                $('<p>'+sql[1]+'</p>').appendTo('#x'+sql[0]);
                $('#x'+sql[0]).css('display','block');
            }else{
                $("#x"+sql[0]).css('display','none');
                $(this).text("+");
            }
  }); 
  //编辑用户权限
            chk_all();
            $("input[name='checkbox[]']").click(function() {
                chk_all();
            })
            $('input[name="all"]').click(
                    function() {
                        var all = $(this).attr('checked');
                        if (all === 'checked') {
                            $("input[name='checkbox[]']").attr('checked', 'checked');
                        } else {
                            $("input[name='checkbox[]']").removeAttr('checked');
                        }
                    }
            );
});
function chk_all() {
        var i = 0;
        var clength = $("input[name='checkbox[]']").length;
        $("input[name='checkbox[]']").each(function() {
            if (this.checked) {
                i++;
            } else {
                return false;
            }
        });
        if (clength == i) {
            $('input[name="all"]').attr('checked', true);
        } else {
            $('input[name="all"]').attr('checked', false);
        }
}
//
function show(username,host,id,moreid) {
    var ida=document.getElementById("ch");
    if(ida===null||ida===undefined){
        $.post(more, { username: username, host: host },
            function(arr){
               if(arr.status){
                   var $st=arr.str;
                   $('#'+id).append($st);
                   $('#'+moreid).text(" ");
               }
            },'json');
    }
}; 
//定义遮罩层
function mask(){
    $( '<div id="windowBG"></div>' ).css({
                width : $(document).width(),
                height : $(document).height(),
                position : 'absolute',
                top : 0,
                left : 0,
                zIndex : 998,
                opacity : 0.3,
                filter : 'Alpha(Opacity = 30)',
                backgroundColor : '#000000'
    }).appendTo( 'body' );
}
//提示框位置
function position(){
        $('.dropdialog').css({
                left : ($(window ).width() - $('.dropdialog').width()*3.5) / 2,
                top : $(document).scrollTop() + ($( window).height() - $('.dropdialog').height()*2)/2
        });
}
//提示框淡入
function dialogshow(key){
    $(".dropdialog").html("<div id='drophead'><b>The Warning</b><div id='close'></div></div><div id='dropcontent'><p><b>Do you really want to "+key+" it?</b></p><div class='dropbottom'><input id='yes' type='button' value='Yes' class='btn'/><input id='no' type='button' value='No' class='btn'/></div></div>");
}
//提示框淡出
function close(selector){
    $(selector).fadeOut('slow',function(){
           $('#windowBG').remove();
    } );
}
//执行删除操作
function del(name,data){
        $(".btn").click(function(){
            var str=$(this).val();
            if(str=="No"){
                close(".dropdialog");
             }else{
                $.post(name,data,function(data){
                        if(data.status){
                            $("#dropcontent").html("<p style='color:#fff;font-size:18px;'>Execute successfully!</p>");
                            $(".dropdialog").delay(1500);
                            close(".dropdialog");
                            if(name.indexOf("drop")>=0 || name.indexOf("delete")>=0 || name.indexOf("del")>=0 ){
                                 $("#del"+index).replaceWith(""); 
                            }
                        }else{
                            $("#dropcontent").html("<p style='color:#fff;font-size:18px;'>Failed!</p>");
                            $(".dropdialog").delay(1500);
                            close(".dropdialog");
                        }
                },'json');
             }
       });
}