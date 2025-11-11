/*
 * 梦奈宝塔主机系统(简称MNBT)
 * 宝塔文件分片上传前端js
*/
function pro(bolb,sizes,path){             //上传文件,此分片文件，上次返回的大小，路径
var formdata = new FormData(); 
formdata.append("file",bolb); 
formdata.append("tempfilename",fileuploadname); 
formdata.append("gn","fileupload"); 
formdata.append("fesw",sizes); 
formdata.append("zsize",file.size); 
formdata.append("htl",path);
let data;
    $.ajax({
        type:"post",
        url:"./ajax.php", 
        data:formdata,
        cache: false,
        processData: false,
        contentType: false,
        async:true,
        success:function(res){
                uploadsfdyc++;
                try{
                    var json= JSON.parse(res);
                }catch(e){
                    msalert(4,'一个意外的错误终止的本次上传！');
                    
                    $.confirm({
                        title: '一个意外的错误',
                        content: res,
                        icon: 'mdi mdi-close-circle',
                        animation: 'scale',
                        closeAnimation: 'scale',
                        opacity: 0.5,
                        type:'red',
                        buttons: {
                            'confirm': {
                                text: '我知道了',
                                btnClass: 'btn-blue'
                            }
                        }
                    });
                    msloadingde();  // 隐藏
                    return;
                }
                if(json.error==1){
                    //上传完成结束
                    msalert(json.size,json.msg,4000);
                    $("#tb_departments").bootstrapTable('refreshOptions',{pageNumber:1});		//刷新表格
                    msloadingde();
                }else{
                    //开始下一片上传
                    if(json.size>=file.size){
                        $(".custom-file-label").html('选择文件...');
                        msalert(4,'出现意外的错误！请删除名称为'+fileuploadname+'.'+file.size+'.upload.tmp的文件！然后重新上传！', 6000);
                        msloadingde();  // 隐藏
                    }else{
                        var filebolb=filefp(json.size);
                        pro(filebolb,json.size,path);
                    }
                }
            }
    });
}
    
function filefp(filesizey){      //文件分片(已上传大小)
    fileupload(filesizey,lengths);       //更新上传提示
    if(uploadsfdyc<=2){
    lengths=lengths;         //下次分片上传的文件大小
    }else{
    lengths=(upxcsize.toFixed(0))*2;         //下次分片上传的文件大小
    }
    var end=filesizey+lengths;               //获取文件下次结尾大小
    if(end>file.size){
        end=file.size;          //如果结尾大于文件大小那么下次结尾就是文件大小。
    }
    var bolb = file.slice(filesizey,end);           //文件分片
    return bolb;
}
    
function zxwjsc(hliy){              //文件上传
var myfile = document.getElementById("myfile");
uploadsfdyc=0;       //已完成上传几次分片
file = myfile.files[0]; 
if(file==null){msalert(3,'请选择要上传的文件！',4000,'#exampleModal');return;}
//判断是否存在
    var selRows = $("#tb_departments").bootstrapTable("getData",{useCurrentPage:true});
    var arrs = '';
    $.each(selRows,function(i) {
        if(this.name==file.name){
            if(this.type=='file'){
            arrs=this;
            }
        }
    });

$('#exampleModal').modal('hide');		//关闭弹窗
if(arrs!=''){
        $.confirm({
        title: '即将覆盖以下文件',
        content: '该目录中存在着与即将上传文件相同名称的文件！请选择操作！<br/><span class="input-group-text">操作类型：<div class="custom-control custom-radio custom-control-inline"><input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" onclick="$(`#input-namesk`).val(file.name); $(`#input-namesk`).attr(`readonly`,`readonly`);"><label class="custom-control-label" for="customRadioInline1">覆盖文件</label></div><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="customRadioInline2" name="customRadioInline" class="custom-control-input" onclick="$(`#input-namesk`).val(file.name+`-副本`); $(`#input-namesk`).removeAttr(`readonly`);" checked><label class="custom-control-label" for="customRadioInline2">重命名文件</label></div></span><div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend2">文件名：</span></div><input type="text" class="form-control border" id="input-namesk"  aria-describedby="inputGroupPrepend2" placeholder="文件名" value="'+file.name+'-副本" required></div><span class="input-group-text">大小：'+sizedwhs(arrs.size)+'</span><span class="input-group-text">最后修改时间：'+getTimes(arrs.mtime)+'</span>',
        icon: 'mdi mdi-comment-question',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        type:'orange',
        buttons: {
            'confirm': {
                text: '确认',
                btnClass: 'btn-blue',
                action: function(){
                    var input = this.$content.find('input#input-namesk').val();
                    fileuploadname=input;
                    uploadcode(hliy);
                    
                }
            },
            '取消': function(){}
        }
    });
}else{
fileuploadname=file.name;
uploadcode(hliy);
}
}

function uploadcode(hliy){          //断点续传验证
lengths = 1024 * 1024 * 1;      //默认每片1MB大小
msloading('文件上传中，共'+sizedwhs(file.size)+'，已上传0.00MB，剩余'+sizedwhs(file.size)+'未上传，当前速度0.00MB/s，预计剩余时间获取中，文件上传进度0.00%');

let data = {};
data["gn"]="file_upload_size";
data["fename"]=fileuploadname;
data["htl"]=hliy;
data["size"]=file.size;
$.post('./ajax.php', data, function (date) {
    try {
        var jsoe= JSON.parse(date);
    } catch (e) {
        msloadingde();
        msalert(4,'系统错误，无法上传');
        return;
    }
    
if(jsoe.code!==1 || jsoe.size===undefined){
    msloadingde();
    msalert(4,jsoe.code);
    return;
}

ot = new Date().getTime();   //设置上传开始时间
var bolb=filefp(jsoe.size);
pro(bolb,jsoe.size,hliy);
})
}

function sizedwhs(size){            //文件单位换算
	var units = 'B';
	if(size/1024>1){
		size = size/1024;
		units = 'KB';
	}
	if(size/1024>1){
		size = size/1024;
		units = 'MB';
	}
	if(size/1024>1){
		size = size/1024;
		units = 'GB';
	}
	return size.toFixed(2)+units;
}

function fileupload(uploadsize,filesz){          //更新上传进度，uploadsize为已上传大小，filesz为当前分片上传大小，单位B
var nt = new Date().getTime();//获取当前时间
	var pertime = (nt-ot)/1000; //计算出上次调用该方法时到现在的时间差，单位为s
	ot = new Date().getTime(); //重新赋值时间，用于下次计算
	//上传速度计算
	var speed = filesz/pertime;
	var bspeed = speed;
	var units = 'B/s';//单位名称
	if(speed/1024>1){
		speed = speed/1024;
		units = 'KB/s';
	}
	if(speed/1024>1){
		speed = speed/1024;
		units = 'MB/s';
	}
	speed = speed.toFixed(2);
	upxcsize=bspeed;
    var resttime = ((file.size-uploadsize)/bspeed).toFixed(2);
	var bfb=Math.round(uploadsize / file.size * 10000) / 100 + "%";
	if(uploadsfdyc<1){
    var msg='文件上传中，共'+sizedwhs(file.size)+'，已上传'+sizedwhs(uploadsize)+'，剩余'+sizedwhs(file.size-uploadsize)+'未上传，当前速度获取中，预计剩余时间获取中，文件上传进度'+bfb;
	}else{
    var msg='文件上传中，共'+sizedwhs(file.size)+'，已上传'+sizedwhs(uploadsize)+'，剩余'+sizedwhs(file.size-uploadsize)+'未上传，当前速度'+speed+units+'，预计剩余时间'+resttime+'秒，文件上传进度'+bfb;
	}
    msloadingup(msg);
}