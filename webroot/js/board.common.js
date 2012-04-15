
var HOSTHOME = "/pay";
var popWindow;
function _$(name)
{
	return document.getElementById(name);
}
function _$$(name)
{
	return document.getElementsByName(name);
}
function _opener$(name)
{
	return opener.document.getElementById(name);
}
function _parent$(name)
{
	return parent.document.getElementById(name);
}

function popups(url,wname,wt,ht,lt,tp,sp)
{
	popWindow = window.open(url,wname, "width="+wt+",height="+ht+",resizable=no,scrollbars="+sp+", left="+lt+",top="+tp);
}

function displayViewChange(block_obj_names,none_obj_names,type)
{
	tmp_block_name = block_obj_names.split(",");
	tmp_none_name = none_obj_names.split(",");

	for(i=0;i<tmp_block_name.length;i++)
	{
		if(tmp_block_name[i] != "")
		{
			if(!type)
				_$(tmp_block_name[i]).style.display = _$(tmp_block_name[i]).style.display == "none" ? "block" : "none";
			else if(type == "fix")
				_$(tmp_block_name[i]).style.display = "block";
		}
	}

	for(i=0;i<tmp_none_name.length;i++)
	{
		if(tmp_none_name[i] != "")
		{
			if(!type)
				_$(tmp_none_name[i]).style.display = _$(tmp_none_name[i]).style.display == "block" ? "none" : "block";
			else if(type == "fix")
				_$(tmp_none_name[i]).style.display = "none";
		}
	}
}




function goOpenPopup(type,pt,thumb,file_size)
{
	//		변수 초기화
	var temp = new Array();
	var obj = new Array();

	//		우편번호 창
	if(type == "file_upload")
	{
		temp['url'] = HOSTHOME + "/board/board_file/upload_form/?pt=" + pt + "&thumb=" + thumb + "&file_size=" + file_size;

		temp['winName'] = "file_upload";
		temp['width'] = "500";
		temp['height'] = "500";
		temp['scroll'] = "0";
		temp['left'] = (screen.width - temp['width']) / 2;
		temp['top'] = (screen.height - temp['height']) / 2;
	}

	popups(temp['url'],temp['winName'],temp['width'],temp['height'],temp['left'],temp['top'],temp['scroll']);
}



function commentConfirm(id,mode)
{
	var temp = new Array();
	var obj = new Array();
	var value = new Array();
	var comment = new Array();
	comment['0'] = "CommentView";
	comment['1'] = "CommentConfirm";
	comment['2'] = "CommentReply";
	comment['3'] = "CommentModify";

	temp['url'] = HOSTHOME + "/board/board_comment/comment_confirm";
	temp['param'] = "data[BoardComment][id]=" + id + "&data[BoardComment][mode]=" + mode;

	$.ajax({ 
		type: "POST", 
		url: temp['url'], 
		data: temp['param'], 
		success : function(msg){

			value = msg.split(",");
			temp['block'] = "";
			temp['none'] = "";
			if(value['0'] == "YES")
			{
				for(i=0;i<4;i++)
				{
					if(comment[i] == value['2']) temp['block'] = comment[i] + value['1'];
					else 
					{
						if(temp['none'] == "") temp['none'] = comment[i] + value['1'];
						else temp['none'] += "," + comment[i] + value['1'];
					}
				}
				displayViewChange(temp['block'], temp['none'],'fix');
			}
			else if(value['0'] == "NO")
			{
				displayViewChange('CommentConfirm' + value['1'], 'CommentView' + value['1'] + ',CommentReply' + value['1'] + ',CommentModify' + value['1'],'fix');
				_$('CommentDataConfirmForm' + value['1']).mode.value = value['2'];
			}
			else if(value['0'] == "FAIL")
			{
				alert("1111");
			}
		}
	});
}



function article_form_check()
{
	var temp = new Array();
	var obj = new Array();

	obj['form'] = _$('DataForm');

	if(obj['form'].board_category_id)
	{
		if(obj['form'].board_category_id.select[obj['form'].board_category_id.selectedIndex].value == "no")
		{
			alert("카테고리를 선택하세요!");
			obj['form'].board_category_id.focus();
			return;
		}
	}

	if(obj['form'].title.value == "")
	{
		alert("제목을 입력하세요!");
		return;
	}

	try
	{
		oEditor = FCKeditorAPI.GetInstance("data[BoardArticle][content]"); 
		obj['form'].content.value = oEditor.GetXHTML( true );
		if(obj['form'].content.value == "")
		{
			alert("내용을 입력하세요!");
			oEditor.Focus();
			return; 
		}
	}
	catch (e)
	{
	}


	if(obj['form'].owner_name.value == "")
	{
		alert("이름을 입력하세요!");
		obj['form'].owner_name.focus();
		return;
	}
	if(obj['form'].user_check.value == "NO")
	{
		if(!obj['form'].user_check.id)
		{
			if(obj['form'].owner_password.value == "")
			{
				alert("비밀번호를 입력하세요!");
				obj['form'].owner_password.focus();
				return;
			}
		}
	}

	obj['form'].submit();
}




function dataFastProc(form_name,data_key_chk_name)
{
	var temp = new Array();
	var obj = new Array();

	temp['together_data'] = "";
	obj['form'] = _$(form_name);

	for(i=0; i<obj['form'].elements.length; i++) 
	{
		temp['ele'] = obj['form'].elements[i];
		if(temp['ele'].name == data_key_chk_name)
		{
			if(temp['ele'].checked == true)
			{
				if(temp['ele'].value != "")
				{
					if(temp['together_data'] == "")
					{
						temp['together_data'] = temp['ele'].value;
					}
					else 
					{
						temp['together_data'] = temp['together_data'] + "," + temp['ele'].value;
					}
				}
			}
		}
	}

	if(temp['together_data'] == "")
	{
		alert("한개이상 선택하셔야 합니다.");
		return;
	}

	temp['check'] = confirm("선택된 데이타를 삭제하시겠습니까?");
	if(temp['check'])
	{
		obj['form'].prkey_value.value = temp['together_data'];
		if(obj['form'].target != "uploadWindow") obj['form'].target = "_self";
		obj['form'].submit();
	}
}