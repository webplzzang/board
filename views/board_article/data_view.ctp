
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'>게시글 보기</td>
				</tr>
			</table>


			<table width='100%' border='1'>
			<col width="150"><col>
			<?if(IsEmpty($db['board_category']['column']['title']) == false){?>
			<tr>
				<td>카테고리</td>
				<td><?=$db['board_category']['column']['title']?></td>
			</tr>
			<?}?>
			<tr>
				<td>제목</td>
				<td>
					<?if(ValueIsCheck($db['data']['column']['subject_bold'],"Y","equal") == true){?><b>
					<?=ValueNullCheck($db['data']['column']['title'])?>
					</b><?}else{?>
					<?=ValueNullCheck($db['data']['column']['title'])?>
					<?}?>
				</td>
			</tr>
			<tr>
				<td>내용</td>
				<td><?=stripslashes(ValueNullCheck($db['data']['column']['content']))?></td>
			</tr>
			<tr>
				<td>작성자 아이디</td>
				<td><?=ValueNullCheck($db['data']['column']['owner_id'])?></td>
			</tr>
			<tr>
				<td>작성자 이름</td>
				<td><?=ValueNullCheck($db['data']['column']['owner_name'])?></td>
			</tr>
			<tr>
				<td>공개</td>
				<td><?if(ValueIsCheck($db['data']['column']['public'],"Y","equal") == true){?> 가<?}else{?> 부<?}?></td>
			</tr>
			<tr>
				<td>비밀글</td>
				<td><?if(ValueIsCheck($db['data']['column']['secret'],"Y","equal") == true){?> 가<?}else{?> 부<?}?></td>
			</tr>
			<?if(IsEmpty($db['board_file']) == false){?>
			<tr>
				<td>첨부</td>
				<td>
					<?for($i=0;$i<sizeof($db['board_file']);$i++){$data = $db['board_file'][$i];?>
					<table width="100%" cellpadding='0' cellspacing='0'>
						<tr>
							<td>
								<?
								if(IsEmpty(getThumbnailName($data['column']['convert'],"2",$data['column']['path'])) == true)
								{
								?>
								<?=$data['column']['original']?>
								<?
								}
								else
								{
								?>
								<img src="<?=HOSTHOME?>/board/board_file/image/<?=base64_encode(APP.'plugins'.DS."board".DS."webroot".DS."files".DS.str_replace("board/files/","",$data['column']['path']).getThumbnailName($data['column']['convert'],"2",$data['column']['path']))?>">
								<?
								}
								?>
							</td>
						</tr>
						<tr>
							<td>
								<a href="<?=HOSTHOME?>/board/board_file/down/<?=base64_encode(APP.'plugins'.DS."board".DS."webroot".DS."files".DS.str_replace("board/files/","",$data['column']['path']).$data['column']['convert'].",".$data['column']['original'])?>">
									[다운]
								</a>
							</td>
						</tr>
					</table>
					<?}?>
				</td>
			</tr>
			<?}?>
			</table>
			</form>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td height='10'></td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>
						<?if($db['board_info']['column']['reply_use'] == "Y"){?>
						<a href="<?=HOSTHOME?>/board/board_article/reply_data_form/<?=$parameter['id']?>/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>">[답글]</a>
						<?}?>
						<a href="<?=HOSTHOME?>/board/board_article/data_form/<?=$parameter['id']?>/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>">[수정]</a>
						<a href='<?=HOSTHOME?>/board/board_article/data_list/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>'>[목록]</a>
					</td>
				</tr>
			</table>



		<?if($db['board_info']['column']['comment_use'] == "Y"){?>
			<table width='100%' border='0'>
			<tr>
				<td height='5'>&nbsp;</td>
			</tr>
			</table>


				<?
				if(sizeof($db['board_comment']) > 0)
				{
					for($i=0;$i<sizeof($db['board_comment']);$i++)
					{
						$data = $db['board_comment'][$i];
				?>
			<table width='100%' border='1' id='CommentView<?=$data['column']['id']?>' style="display:block">
				<tr>
					<td align='left'><?=$data['space']." ".$data['re']?></td>
					<td>
						<table width='100%'>
						<tr>
							<td align='left'>
								<?=ValueNullCheck($data['column']['owner_name'])?> <?=date("Y-m-d",$data['column']['write'])?>
								<!--
								<a href="javascript:displayViewChange('CommentModify<?=$data['column']['id']?>','CommentView<?=$data['column']['id']?>,CommentReply<?=$data['column']['id']?>','fix');">수정</a>
								-->
								<a href="javascript:commentConfirm(<?=$data['column']['id']?>,'CommentModify');">수정</a>
								
								<a onclick="var chk;chk=confirm('<?=Configure::read('description.confirm_delete')?>');if(chk){_$('CommentDataFormDelete').id.value='<?=$data['column']['id']?>';_$('CommentDataFormDelete').id.value='<?=$data['column']['id']?>';_$('CommentDataFormDelete').submit();}" style='cursor:pointer'>삭제</a>
								<a href="javascript:displayViewChange('CommentReply<?=$data['column']['id']?>','CommentView<?=$data['column']['id']?>,CommentModify<?=$data['column']['id']?>','fix');">답글</a>
							</td>
						</tr>
						<tr>
							<td align='left'><?=nl2br(stripslashes(ValueNullCheck($data['column']['content'])))?></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height='1' bgcolor='black' colspan='2'></td>
				</tr>
			</table>
			<table width='100%' border='1' id='CommentModify<?=$data['column']['id']?>' style="display:none">
			<form name='CommentDataModifyForm<?=$data['column']['id']?>' id='CommentDataModifyForm<?=$data['column']['id']?>' method='post' action='<?=HOSTHOME?>/board/board_comment/data_process'>
			<input type='hidden' name='data[BoardComment][id]' value="<?=$data['column']['id']?>">
			<input type='hidden' name='data[BoardComment][board_article_id]' value="<?=$db['data']['column']['id']?>">
			<input type='hidden' name='data[BoardComment][board_info_id]' value="<?=$db['data']['column']['board_info_id']?>">
			<input type='hidden' name='data[BoardComment][board_category_id]' value="<?=$db['data']['column']['board_category_id']?>">
			<input type='hidden' name='data[BoardComment][data_page]' value="<?=$parameter['data_page']?>">

			<?if($_board_user['check'] == "YES"){?>
			<input type='hidden' name='data[BoardComment][owner_id]' value="<?=$db['data']['column']['owner_id']?>">
			<input type='hidden' name='data[BoardComment][owner_name]' value="<?=$db['data']['column']['owner_name']?>">
			<input type='hidden' name='data[BoardComment][owner_password]' value="">
			<?}?>
				<tr>
					<td>내용</td>
					<td>
						<textarea name="data[BoardComment][content]" rows="2" cols="40" style="width: 95%; height: 100px"><?=stripslashes(ValueNullCheck($data['column']['content']))?></textarea>
					</td>
				</tr>

				<?if($_board_user['check'] == "NO"){?>
				<tr>
					<td>작성자 아이디</td>
					<td><input type='text' name='data[BoardComment][owner_id]' size='20' value="<?=ValueNullCheck($data['column']['owner_id'])?>"></td>
				</tr>
				<tr>
					<td>작성자 이름</td>
					<td><input type='text' name='data[BoardComment][owner_name]' size='20' value="<?=ValueNullCheck($data['column']['owner_name'])?>"></td>
				</tr>
				<tr>
					<td>작성자 비밀번호</td>
					<td><input type='password' name='data[BoardComment][owner_password]' size='20' value=""></td>
				</tr>
				<?}?>

				<tr>
					<td colspan='2'>
						<a href="javascript:_$('CommentDataModifyForm<?=$data['column']['id']?>').submit();">[저장]</a>
						<a href="javascript:displayViewChange('CommentView<?=$data['column']['id']?>', 'CommentConfirm<?=$data['column']['id']?>,CommentModify<?=$data['column']['id']?>,CommentReply<?=$data['column']['id']?>','fix');">[취소]</a>
					</td>
				</tr>
				<tr>
					<td height='1' bgcolor='black' colspan='2'></td>
				</tr>
			</form>
			</table>

			<table width='100%' border='1' id='CommentConfirm<?=$data['column']['id']?>' style="display:none">
			<form name='CommentDataConfirmForm<?=$data['column']['id']?>' id='CommentDataConfirmForm<?=$data['column']['id']?>' method='post' action='<?=HOSTHOME?>/board/board_comment/confirm_data_process' target="uploadWindow">
			<input type='hidden' name='data[BoardComment][id]' value="<?=$data['column']['id']?>">
			<input type='hidden' name='data[BoardComment][mode]' id='mode' value="">
				<tr>
					<td>비밀번호</td>
					<td>
						<input type='text' name='data[BoardComment][confirm_password]' id='confirm_password' size='40' value="">
					</td>
				</tr>

				<tr>
					<td colspan='2'>
						<a href="javascript:_$('CommentDataConfirmForm<?=$data['column']['id']?>').submit();">[확인]</a>
						<a href="javascript:displayViewChange('CommentView<?=$data['column']['id']?>', 'CommentConfirm<?=$data['column']['id']?>,CommentReply<?=$data['column']['id']?>,CommentModify<?=$data['column']['id']?>','fix');">[취소]</a>
					</td>
				</tr>
				<tr>
					<td height='1' bgcolor='black' colspan='2'></td>
				</tr>
			</form>
			</table>



			<table width='100%' border='1' id='CommentReply<?=$data['column']['id']?>' style="display:none">
			<form name='CommentDataReplyForm<?=$data['column']['id']?>' id='CommentDataReplyForm<?=$data['column']['id']?>' method='post' action='<?=HOSTHOME?>/board/board_comment/reply_data_process'>
			<input type='hidden' name='data[BoardComment][user_check]' value="<?=$_board_user['check']?>">
			<input type='hidden' name='data[BoardComment][parent_id]' value="<?=$data['column']['id']?>">
			<input type='hidden' name='data[BoardComment][parent_family]' value="<?=$data['column']['family']?>">
			<input type='hidden' name='data[BoardComment][parent_field_sort]' value="<?=$data['column']['field_sort']?>">
			<input type='hidden' name='data[BoardComment][parent_depth]' value="<?=$data['column']['depth']?>">
			<input type='hidden' name='data[BoardComment][board_article_id]' value="<?=$db['data']['column']['id']?>">
			<input type='hidden' name='data[BoardComment][board_info_id]' value="<?=$db['data']['column']['board_info_id']?>">
			<input type='hidden' name='data[BoardComment][board_category_id]' value="<?=$db['data']['column']['board_category_id']?>">
			<input type='hidden' name='data[BoardComment][data_page]' value="<?=$parameter['data_page']?>">

			<?if($_board_user['check'] == "YES"){?>
			<input type='hidden' name='data[BoardComment][owner_id]' value="<?=$_board_user['id']?>">
			<input type='hidden' name='data[BoardComment][owner_name]' value="<?=$_board_user['name']?>">
			<input type='hidden' name='data[BoardComment][owner_password]' value="<?=$_board_user['password']?>">
			<?}?>

				<tr>
					<td>내용</td>
					<td>
						<textarea name="data[BoardComment][content]" rows="2" cols="40" style="width: 95%; height: 100px"></textarea>
					</td>
				</tr>

				<?if($_board_user['check'] == "NO"){?>
				<tr>
					<td>작성자 아이디</td>
					<td><input type='text' name='data[BoardComment][owner_id]' size='20' value=""></td>
				</tr>
				<tr>
					<td>작성자 이름</td>
					<td><input type='text' name='data[BoardComment][owner_name]' size='20' value=""></td>
				</tr>
				<tr>
					<td>작성자 비밀번호</td>
					<td><input type='password' name='data[BoardComment][owner_password]' size='20' value=""></td>
				</tr>
				<?}?>

				<tr>
					<td colspan='2'>
						<a href="javascript:_$('CommentDataReplyForm<?=$data['column']['id']?>').submit();">[저장]</a>
						<a href="javascript:displayViewChange('CommentView<?=$data['column']['id']?>', 'CommentConfirm<?=$data['column']['id']?>,CommentModify<?=$data['column']['id']?>,CommentReply<?=$data['column']['id']?>','fix');">[취소]</a>
					</td>
				</tr>
				<tr>
					<td height='1' bgcolor='black' colspan='2'></td>
				</tr>
			</form>
			</table>

				<?
						unset($data);
					}
				}
				?>


			<form name='CommentDataForm' id='CommentDataForm' method='post' action='<?=HOSTHOME?>/board/board_comment/data_process'>
			<input type='hidden' name='data[BoardComment][user_check]' value="<?=$_board_user['check']?>">
			<input type='hidden' name='data[BoardComment][board_article_id]' value="<?=$db['data']['column']['id']?>">
			<input type='hidden' name='data[BoardComment][board_info_id]' value="<?=$db['data']['column']['board_info_id']?>">
			<input type='hidden' name='data[BoardComment][board_category_id]' value="<?=$db['data']['column']['board_category_id']?>">
			<input type='hidden' name='data[BoardComment][data_page]' value="<?=$parameter['data_page']?>">

			<?if($_board_user['check'] == "YES"){?>
			<input type='hidden' name='data[BoardComment][owner_id]' value="<?=$_board_user['id']?>">
			<input type='hidden' name='data[BoardComment][owner_name]' value="<?=$_board_user['name']?>">
			<input type='hidden' name='data[BoardComment][owner_password]' value="<?=$_board_user['password']?>">
			<?}?>

			<table width='100%' border='1'>
			<col width="150"><col>
			<tr>
				<td>내용</td>
				<td>
					<?if($db['board_info']['column']['comment'] == "no" || ($db['board_info']['column']['comment'] == "user" && $_board_user['check'] == "YES") || ($db['board_info']['column']['comment'] == "admin" && $_board_user['admin'] == "YES")){?>
					<textarea name="data[BoardComment][content]" rows="2" cols="40" style="width: 95%; height: 100px"></textarea>
					<?}else{?>
					<textarea name="data[BoardComment][content]" rows="2" cols="40" style="width: 95%; height: 100px"><?if($db['board_info']['column']['comment'] == "user"){?>로그인이 필요합니다!<?}else{?>권한이 없습니다!<?}?></textarea>
					<?}?>
				</td>
			</tr>

			<?if($_board_user['check'] == "NO"){?>
			<tr>
				<td>작성자 아이디</td>
				<td><input type='text' name='data[BoardComment][owner_id]' size='20' value=""></td>
			</tr>
			<tr>
				<td>작성자 이름</td>
				<td><input type='text' name='data[BoardComment][owner_name]' size='20' value=""></td>
			</tr>
			<tr>
				<td>작성자 비밀번호</td>
				<td><input type='password' name='data[BoardComment][owner_password]' size='20' value=""></td>
			</tr>
			<?}?>

			</table>
			</form>

			<?if($db['board_info']['column']['comment'] == "no" || ($db['board_info']['column']['comment'] == "user" && $_board_user['check'] == "YES") || ($db['board_info']['column']['comment'] == "admin" && $_board_user['admin'] == "YES")){?>
			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>
						<a href="javascript:_$('CommentDataForm').submit();">[저장]</a>
						<a href='<?=HOSTHOME?>/board/board_article/data_list/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>'>[취소]</a>
					</td>
				</tr>
			</table>
			<?}?>


			<form name='CommentDataFormDelete' id='CommentDataFormDelete' method='post' action='<?=HOSTHOME?>/board/board_comment/data_delete'>
			<input type='hidden' name='data[BoardComment][id]' id='id'>
			<input type='hidden' name='data[BoardComment][board_article_id]' value="<?=$db['data']['column']['id']?>">
			<input type='hidden' name='data[BoardComment][board_info_id]' value="<?=$db['data']['column']['board_info_id']?>">
			<input type='hidden' name='data[BoardComment][board_category_id]' value="<?=$db['data']['column']['board_category_id']?>">
			</form>
		<?}?>

			<iframe name="uploadWindow" width='0' height="0" frameborder='0' scrolling='no'></iframe>