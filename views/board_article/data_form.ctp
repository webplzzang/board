
			<?=$this->Html->script(array('/board/js/board.common','/board/js/swfupload/swfbase','/board/js/swfupload/swfupload','/board/js/swfupload/js/swfupload.queue','/board/js/swfupload/js/fileprogress','/board/js/swfupload/js/handlers'))?>

			<?
			if($db['board_info']['column']['file_use'] == "Y" && $db['board_info']['column']['file_ea'] > 0 && appleMobileCheck() == false)
			{
				$str = "window.onload = function() {";
				for($i=1;$i<=$db['board_info']['column']['file_ea'];$i++) $str.= "_init('".$html->base.DS.$html->plugin."',".$i.",'".$this -> Session -> id()."',_$('thumb_check').value,'".$db['board_info']['column']['file_size']." B');";
				$str.="}";
				$str.="
				function returnStrParsing(val)
				{
					var temp = val.split(',');
//					_$('contentsByAdd' + temp['0']).innerHTML=temp['4'];
					_$('file_add_root' + temp['0']).value=temp['1'];
					_$('file_add_path' + temp['0']).value=temp['2'];
					_$('file_add_convert' + temp['0']).value=temp['3'];
					_$('file_add_original' + temp['0']).value=temp['4'];
					return;
				}
				";
				echo $this->Html->scriptBlock($str);
			}
			?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<? if(IsEmpty($db['data']) == false) {?>
					<td align='center'>게시글 수정</td>
					<?} else {?>
					<td align='center'>게시글 등록</td>
					<?}?>
				</tr>
			</table>


			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_article/data_process'>
			<input type='hidden' name='data[BoardArticle][type]' id='type' value="<?=$db['board_info']['column']['type']?>">
			<input type='hidden' name='data[BoardArticle][thumb_check]' id='thumb_check' value="<?=$db['board_info']['column']['thumbnail_use']?>">
			<input type='hidden' name='data[BoardArticle][file_ea]' id='file_ea' value="<?=$db['board_info']['column']['file_ea']?>">
			<input type='hidden' name='data[BoardArticle][user_check]' id='user_check' value="<?=$_board_user['check']?>">
			<input type='hidden' name='data[BoardArticle][board_info_id]' value="<?=$db['board_info']['column']['id']?>">
			<?if(IsEmpty($db['data']) == false) {?>
			<input type='hidden' name='data[BoardArticle][id]' id='id' value="<?=$db['data']['column']['id']?>">
			<input type='hidden' name='data[BoardArticle][owner_id]' id='owner_id' value="<?=ValueNullCheck($db['data']['column']['owner_id'])?>">
			<input type='hidden' name='data[BoardArticle][owner_name]' id='owner_name' value="<?=ValueNullCheck($db['data']['column']['owner_name'])?>">
			<input type='hidden' name='data[BoardArticle][owner_password]' id='owner_password' value="">
			<?}?>
			<?if($_board_user['check'] == "YES" && IsEmpty($db['data']) == true){?>
			<input type='hidden' name='data[BoardArticle][owner_id]' id='owner_id' value="<?=$_board_user['id']?>">
			<input type='hidden' name='data[BoardArticle][owner_name]' id='owner_name' value="<?=$_board_user['name']?>">
			<input type='hidden' name='data[BoardArticle][owner_password]' id='owner_password' value="<?=$_board_user['password']?>">
			<?}?>

			<?if(IsEmpty($db['board_category']) == true){?>
			<input type='hidden' name='data[BoardArticle][board_category_id]' value="NO">
			<?}?>

			<table width='100%' border='1'>
			<col width="150"><col>

			<?if(IsEmpty($db['board_category']) == false){?>
			<tr>
				<td>카테고리</td>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0' border='0'>
						<tr>
							<td align='left'>
								<select name='data[BoardArticle][board_category_id]'>
								<option value="no"<?if(ValueIsCheck($parameter['board_category_id'],"no","equal") == true || IsEmpty($parameter['board_category_id']) == true){?> selected<?}?>>전체</option>
								<?
								for($i=0;$i<sizeof($db['board_category']);$i++)
								{
									$data = $db['board_category'][$i];
									if(IsEmpty($db['data']) == false) {
								?>
									<option value="<?=$data['column']['id']?>"<?if(ValueIsCheck($db['data']['column']['board_category_id'],$data['column']['id'],"equal") == true){?> selected<?}?>><?=$data['column']['title']?></option>
								<?
									}
									else {
								?>
									<option value="<?=$data['column']['id']?>"<?if(ValueIsCheck($parameter['board_category_id'],$data['column']['id'],"equal") == true){?> selected<?}?>><?=$data['column']['title']?></option>
								<?
									}
								}
								?>
								</select>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?}?>

			<tr>
				<td>제목</td>
				<td><input type='text' name='data[BoardArticle][title]' id='title' size='40' value="<?=ValueNullCheck($db['data']['column']['title'])?>"></td>
			</tr>
			<tr>
				<td>내용</td>
				<td>
					<? echo $fck->fckeditor(array('BoardArticle', 'content'), $html->base.DS.$html->plugin, ValueNullCheck($db['data']['column']['content'])); ?>
				</td>
			</tr>

			<?if($_board_user['check'] == "NO"){?>
			<tr>
				<td>작성자 아이디</td>
				<td><input type='text' name='data[BoardArticle][owner_id]' id='owner_id' size='20' value="<?=ValueNullCheck($db['data']['column']['owner_id'])?>"></td>
			</tr>
			<tr>
				<td>작성자 이름</td>
				<td><input type='text' name='data[BoardArticle][owner_name]' id='owner_name' size='20' value="<?=ValueNullCheck($db['data']['column']['owner_name'])?>"></td>
			</tr>
			<tr>
				<td>작성자 비밀번호</td>
				<td><input type='password' name='data[BoardArticle][owner_password]' id='owner_password' size='20' value=""></td>
			</tr>
			<?}?>

			<tr>
				<td>공개</td>
				<td>
					<input type='radio' name='data[BoardArticle][public]' value='Y'<?if(ValueIsCheck($db['data']['column']['public'],"Y","equal") == true){?> checked<?}?>>가
					<input type='radio' name='data[BoardArticle][public]' value='N'<?if(ValueIsCheck($db['data']['column']['public'],"N","equal") == true || IsEmpty($db['data']['column']['public']) == true){?> checked<?}?>>부
				</td>
			</tr>
			<tr>
				<td>비밀글</td>
				<td>
					<input type='radio' name='data[BoardArticle][secret]' value='Y'<?if(ValueIsCheck($db['data']['column']['secret'],"Y","equal") == true){?> checked<?}?>>가
					<input type='radio' name='data[BoardArticle][secret]' value='N'<?if(ValueIsCheck($db['data']['column']['secret'],"N","equal") == true || IsEmpty($db['data']['column']['secret']) == true){?> checked<?}?>>부
				</td>
			</tr>
			<tr>
				<td>제목굴게</td>
				<td>
					<input type='radio' name='data[BoardArticle][subject_bold]' value='Y'<?if(ValueIsCheck($db['data']['column']['subject_bold'],"Y","equal") == true){?> checked<?}?>>가
					<input type='radio' name='data[BoardArticle][subject_bold]' value='N'<?if(ValueIsCheck($db['data']['column']['subject_bold'],"N","equal") == true || IsEmpty($db['data']['column']['subject_bold']) == true){?> checked<?}?>>부
				</td>
			</tr>

			<?
			if($db['board_info']['column']['file_use'] == "Y")
			{
				for($i=1;$i<=$db['board_info']['column']['file_ea'];$i++)
				{
					$pt = $i - 1;
					$data = ValueNullCheck($db['board_file'][$pt]);
			?>
			<tr>
				<td>첨부파일</td>
				<td>
					<div id='contentsByAdd<?=$i?>'><?=$data['column']['original']?></div>
					<input type='hidden' name='data[BoardArticle][file_add_id<?=$i?>]' id='file_add_id<?=$i?>' value="<?=$data['column']['id']?>">
					<input type='hidden' name='data[BoardArticle][file_add_root<?=$i?>]' id='file_add_root<?=$i?>' value="<?=$data['column']['root']?>">
					<input type='hidden' name='data[BoardArticle][file_add_path<?=$i?>]' id='file_add_path<?=$i?>' value="<?=$data['column']['path']?>">
					<input type='hidden' name='data[BoardArticle][file_add_convert<?=$i?>]' id='file_add_convert<?=$i?>' value="<?=$data['column']['convert']?>">
					<input type='hidden' name='data[BoardArticle][file_add_original<?=$i?>]' id='file_add_original<?=$i?>' value="<?=$data['column']['original']?>">
					<?if(appleMobileCheck() == true){?>
					<input type='button' value='첨부' onclick="goOpenPopup('file_upload','<?=$i?>',_$('thumb_check').value,<?=$db['board_info']['column']['file_size']?>);">
					<?}else{?>
					<div class="fieldset flash" id="fsUploadProgress<?=$i?>">
					<?}?>
					<span class="legend"></span>
					</div>
					<span id="spanButtonPlaceHolder<?=$i?>"></span>
				</td>
			</tr>
			<?
				}
			}
			?>
			</table>
			</form>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td height='10'>
					</td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>
						<a href="javascript:article_form_check();">[저장]</a>
						<a href='<?=HOSTHOME?>/board/board_article/data_list/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>'>[취소]</a>
					</td>
				</tr>
			</table>