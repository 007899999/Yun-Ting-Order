var customPropId = -1;//一级属性id
var customPropValId = -1;//二级属性id

var alreadySetSkuVals = {};//已经设置的表格值数据
$(function(){
    //------------------------------------------------点击添加一级属性 (克隆SKU模板生成自定义sku)
    $(document).on("click" , ".cloneSku" , function(){
        var cloneSource = $("#skuCloneModel");//要克隆的sku模板
        var cloneNode = cloneSource.clone(true);//克隆出来的节点
        cloneNode.css("display","block");//显示元素
        //移除id
        cloneNode.removeAttr("id");
        customPropId -- ;
        //设置自定义属性类型主键
        $(cloneNode).find(".cusSkuTypeInput").parent().attr("propid",customPropId);
        //设置SKU类型主键 propid
        $(cloneNode).find(".cusSkuValInput").prev().attr("propid",customPropId);
        customPropValId -- ;
        //设置自定义属性类型值主键
        $(cloneNode).find(".cusSkuValInput").prev().attr("propvalid",customPropValId);
        $(this).before(cloneNode);//添加到该按钮的前面
    });

    //---------------------------------------------------------------------点击添加二级属性
    $(document).on("click",".cloneSkuVal",function(){
        //要克隆的SKU值模板
        var cloneSource = $("#onlySkuValCloneModel");
        //克隆出来的节点
        var cloneNode = cloneSource.clone(true);
        //移除id
        cloneNode.removeAttr("id");
        //获取并设置SKU类型主键
        var propid = $(this).parents("ul").prev().find("li").attr("propid");
        $(cloneNode).find(".cusSkuValInput").prev().attr("propid",propid);
        customPropValId -- ;
        //设置自定义属性类型值主键
        $(cloneNode).find(".cusSkuValInput").prev().attr("propvalid",customPropValId);
        //显示元素
        cloneNode.css("display","block");
        //添加到该按钮的前面
        $(this).before(cloneNode);
    });

    //--------------------------------------------------------------一级属性input框改变(SKU类型改变)
    $(document).on("change", ".cusSkuTypeInput", function(){
        baseSuk($(this));
    });
    //初始值
    $("ul[class*='SKU_TYPE']").find("li").find("input").each(function(){                    //循环已有的一级属性
        baseSuk($(this));
    });
    function baseSuk(eml) {
        //判断当前的SKU类型是否已经存在
        var isHaveThisSkuType = false;
        var customSkuTypeName = eml.val();
        if(customSkuTypeName){
            $("ul[class*='SKU_TYPE']").find("li").each(function(){                    //循环已有的一级属性
                var currSkuTypeName = $(this).attr("sku-type-name");//当前SKU类型名称
                if(currSkuTypeName == customSkuTypeName){							//要添加的一级属性==已有的一级属性
                    isHaveThisSkuType = true;
                }
            });
        }
        if(isHaveThisSkuType){
            layer.alert("该SKU类型已经存在!");
            eml.val("");
        }
        eml.parent().attr("sku-type-name",eml.val());   //一级属性存在时，就赋值给对应的ul
        //一级栏目值不存在/重复时，它所属的二级栏目也要清除
        if(!eml.val() || isHaveThisSkuType){
            eml.parent().parent().next().find("input[type='checkbox'][class*='sku_value']").each(function(){
                //取消选中
                eml.attr("checked",false)
                //移除class
                eml.removeClass("sku_value");
            });
        };
    }

    //----------------------------------------------------------二级属性input框改变(SKU值改变)
    $(document).on("change", ".cusSkuValInput", function(){
        var isHaveSkuVal = false;//是否已经存在当前的SKU值
        var thisSkuVal = $(this).val();
        //二级栏目名称已经重复
        $(".model_sku_val,.sku_value").each(function(){
            var customSkuVal = $(this).val();//当前SKU值
            if(thisSkuVal == customSkuVal){
                isHaveSkuVal = true;
                return;
            }
        });

        if(isHaveSkuVal){
            layer.alert("该SKU类型已经存在!");
            $(this).val("");
        };

        $("input[type*='checkbox'][class*='']")
        var cusSkuVal = $(this).val();
        //二级栏目名称重复/不存在时
        if(!cusSkuVal || isHaveSkuVal){
            //移除class
            $(this).prev().removeClass("sku_value");
            if($(this).prev().is(":checked")){
                //移除选中
                $(this).prev().attr("checked",false);
            }
        }
        //二级栏目名称存在时就赋值给checkbox
        $(this).prev().val(cusSkuVal);
    });

    $(".emmmmmmmmmmm").each(function(){
        baseSKUSecond($(this));
    });
    function baseSKUSecond(eml){
        var isHaveSkuVal = false;//是否已经存在当前的SKU值
        var thisSkuVal = eml.val();
        //二级栏目名称已经重复
        $(".model_sku_val,.sku_value").each(function(){
            var customSkuVal = $(this).val();//当前SKU值
            if(thisSkuVal == customSkuVal){
                isHaveSkuVal = true;
                return;
            }
        });

        if(isHaveSkuVal){
            layer.alert("该SKU类型已经存在!");
            eml.val("");
        };

        $("input[type*='checkbox'][class*='']")
        var cusSkuVal = eml.val();
        //二级栏目名称重复/不存在时
        if(!cusSkuVal || isHaveSkuVal){
            //移除class
            eml.prev().removeClass("sku_value");
            if(eml.prev().is(":checked")){
                //移除选中
                eml.prev().attr("checked",false);
            }
        }
        //二级栏目名称存在时就赋值给checkbox
        eml.prev().val(cusSkuVal);
    }

    //二级属性checkbox框change事件
    $(document).on("change",".model_sku_val",function(){
        var secondAttrInputVal=$(this).next().val();
        console.log('二级内容',secondAttrInputVal);

        //SKU类型
        var skuTypeVal = $(this).parent().parent().prev(".SKU_TYPE").find("li").attr("sku-type-name");   //一级栏目
        console.log($(this).parent().parent().find("li"));
        //是否设置了sku类型及sku值
        if(skuTypeVal && $(this).val()&&secondAttrInputVal){     //一级属性有值，并且二级栏目checked==true时
            //添加class
            $(this).addClass("sku_value");   //重绘表格时循环的是sku_value
            refreshTable();
        }else{
            // console.log(skuTypeVal,$(this).val(),secondAttrInputVal);
            $(this).attr("checked",false);
            return layer.alert('请先填写一级属性，再填写二级属性，最后勾选！');
        }
    });

    //删除自定义sku类型模块
    $(document).on("click",".delCusSkuType",function(){
        $(this).parent().parent().parent().remove();               //删除 一级属性
        refreshTable();                                            //触发change事件,重绘表格
    });

    //删除自定义sku值
    $(document).on("click",".delCusSkuVal",function(){         //删除二级属性
        $(this).parent().remove();
        refreshTable();                                          //触发change事件,重绘表格
    });

    //重绘表格
    function refreshTable(){
        getAlreadySetSkuVals();//获取已经设置的SKU值
//		console.log(alreadySetSkuVals);
        var skuTypeArr =  [];//存放SKU类型的数组
        var totalRow = 1;//总行数
        //获取元素类型
        $(".SKU_TYPE").each(function(){
            //SKU类型节点
            var skuTypeNode = $(this).children("li");
            var skuTypeObj = {};//sku类型对象
            //SKU属性类型标题
            skuTypeObj.skuTypeTitle = $(skuTypeNode).attr("sku-type-name");
            //SKU属性类型主键
            var propid = $(skuTypeNode).attr("propid");
            skuTypeObj.skuTypeKey = propid;
            skuValueArr = [];//存放SKU值得数组
            //SKU相对应的节点
            var skuValNode = $(this).next();
            //获取SKU值
            var skuValCheckBoxs = $(skuValNode).find("input[type='checkbox'][class*='sku_value']");
            $(skuValCheckBoxs).each(function(){
                if($(this).is(":checked")){
                    var skuValObj = {};//SKU值对象
                    skuValObj.skuValueTitle = $(this).val();//SKU值名称
                    skuValObj.skuValueId = $(this).attr("propvalid");//SKU值主键
                    skuValObj.skuPropId = $(this).attr("propid");//SKU类型主键
                    skuValueArr.push(skuValObj);
                }
            });
            if(skuValueArr && skuValueArr.length > 0){
                totalRow = totalRow * skuValueArr.length;
                skuTypeObj.skuValues = skuValueArr;//sku值数组
                skuTypeObj.skuValueLen = skuValueArr.length;//sku值长度
                skuTypeArr.push(skuTypeObj);//保存进数组中
            }
        });

        var SKUTableDom = "";//sku表格数据
        SKUTableDom += "<table class='skuTable'><tr>";
        //创建表头
        if( skuTypeArr.length==0){
            $("#skuTable").html('');
            return;
        };
        for(var t = 0 ; t < skuTypeArr.length ; t ++){
            SKUTableDom += '<th>'+skuTypeArr[t].skuTypeTitle+'</th>';
        }
        SKUTableDom += '<th>展示图</th><th>售价</th><th>原料用量/现有库存</th><th>最低库存</th><th>超时提醒时间</th>';
        SKUTableDom += "</tr>";
        //循环处理表体
        for(var i = 0 ; i < totalRow ; i ++){//总共需要创建多少行
            var currRowDoms = "";
            var rowCount = 1;//记录行数
            var propvalidArr = [];//记录SKU值主键
            var propIdArr = [];//属性类型主键
            var propvalnameArr = [];//记录SKU值标题
            var propNameArr = [];//属性类型标题
            for(var j = 0 ; j < skuTypeArr.length ; j ++){//sku列
                var skuValues = skuTypeArr[j].skuValues;//SKU值数组
                var skuValueLen = skuValues.length;//sku值长度
                rowCount = (rowCount * skuValueLen);//目前的生成的总行数
                var anInterBankNum = (totalRow / rowCount);//跨行数
                var point = ((i / anInterBankNum) % skuValueLen);
                propNameArr.push(skuTypeArr[j].skuTypeTitle);
                if(0  == (i % anInterBankNum)){//需要创建td
                    currRowDoms += '<td rowspan='+anInterBankNum+'>'+skuValues[point].skuValueTitle+'</td>';
                    propvalidArr.push(skuValues[point].skuValueId);
                    propIdArr.push(skuValues[point].skuPropId);
                    propvalnameArr.push(skuValues[point].skuValueTitle);
                }else{
                    //当前单元格为跨行
                    propvalidArr.push(skuValues[parseInt(point)].skuValueId);
                    propIdArr.push(skuValues[parseInt(point)].skuPropId);
                    propvalnameArr.push(skuValues[parseInt(point)].skuValueTitle);
                }
            }
//
            var propvalids = propvalidArr.toString()
            var proImg="";          //属性图片
            var price="";          //售价
            var raw1="";           //原料用量/现有库存
            var min_stock="";     //最低库存
            var remindtime="";    //超时提醒时间  单位为分钟
            //赋值
            if(alreadySetSkuVals){
                var currGroupSkuVal = alreadySetSkuVals[propvalids];//当前这组SKU值
                if(currGroupSkuVal){
                    proImg=currGroupSkuVal.proImg;
                    price=currGroupSkuVal.price;
                    raw1=currGroupSkuVal.raw1
                    min_stock=currGroupSkuVal.min_stock;
                    remindtime=currGroupSkuVal.remindtime;
                }
            }
            //console.log(propvalids);
            SKUTableDom += '<tr propvalids=\''+propvalids+'\' propids=\''+propIdArr.toString()+'\' propvalnames=\''+propvalnameArr.join(";")+'\'  propnames=\''+propNameArr.join(";")+'\' class="sku_table_tr">'+currRowDoms+
                // '<td><input type="file" class="setting_img" onchange="showPreviews(this)" value="'+proImg+'"/></td>'+
                '<td><input type="file" class="setting_img" value="'+proImg+'" onchange="showPreviews(this)" img-url="'+proImg+'"/></td>'+
                '<td><input type="number" class="setting_price" value="'+price+'"/></td>'+
                '<td><input type="number" class="setting_raw1" value="'+raw1+'"/></td>'+
                '<td><input type="number" class="setting_min_stock" value="'+min_stock+'"/></td>'+
                '<td><input type="number" placeholder="单位：分钟" class="setting_remindtime" value="'+remindtime+'"/></td>'+
                '</tr>';
        }
        SKUTableDom += "</table>";
        $("#skuTable").html(SKUTableDom);
//			console.log(skuTypeArr);
    }


    /**
     * 获取已经设置的SKU值 (之前设置的价格库存)
     */
    function getAlreadySetSkuVals(){
        alreadySetSkuVals = {};
        //获取设置的SKU属性值
        $("tr[class*='sku_table_tr']").each(function(){
            var proImg=$(this).find("input[type='file'][class*='setting_img']").val();       //图片
            var price=$(this).find("input[type='number'][class*='setting_price']").val();    //单价
            var raw1 = $(this).find("input[type='number'][class*='setting_raw1']").val();  //现有库存/原料用量
            var min_stock= $(this).find("input[type='number'][class*='setting_min_stock']").val(); //最低库存
            var remindtime = $(this).find("input[type='number'][class*='setting_remindtime']").val();    //超时提醒时间  单位为分钟
//		if(skuPrice || skuStock){//已经设置了全部或部分值
            var propvalids = $(this).attr("propvalids");//SKU值主键集合
            alreadySetSkuVals[propvalids] = {
                "proImg":proImg,
                "price":price,
                "raw1":raw1,
                "min_stock":min_stock,
                "remindtime":remindtime
            };
//		}
        });
    }


//获取已经设置的SKU
    $(".model_sku_val sku_value").on("click",function(){
        var testArray=[];
        $("tr[class*='sku_table_tr']").each(function(){
            var obj={propnames:'',propvalnames:'',proImg:'',price:'',raw1:'',min_stock:'',remindtime:''};
            obj.proImg=$(this).find("input[type='file'][class*='setting_img']").val();        //图片
            obj.price=$(this).find("input[type='number'][class*='setting_price']").val();      //价格
            obj.raw1 = $(this).find("input[type='number'][class*='setting_raw1']").val();  //现有库存/原料用量
            obj.min_stock= $(this).find("input[type='number'][class*='setting_min_stock']").val();//最低库存
            obj.remindtime= $(this).find("input[type='text'][class*='setting_remindtime']").val();//超时提醒时间  单位为分钟
            obj.propnames= $(this).attr("propnames");  //一级属性名称
            obj.propvalnames= $(this).attr("propvalnames");  //二级属性名称
            testArray.push(obj);
        });
        document.getElementById('skuTableshi').value = testArray;
        //console.log(testArray);
    });


})