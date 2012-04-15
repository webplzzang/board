
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'>게시글 답글 등록</td>
				</tr>
			</table>


			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_article/reply_data_process'>
			<input type='hidden' name='data[BoardArticle][user_check]' value="<?=$_board_user['check']?>">
			<input type='hidden' name='data[BoardArticle][board_info_id]' value="<?=$db['board_info']['column']['id']?>">
			<input type='hidden' name='data[BoardArticle][parent_id]' value="<?=$db['parent_data']['column']['id']?>">
			<input type='hidden' name='data[BoardArticle][parent_family]' value="<?=$db['parent_data']['column']['family']?>">
			<input type='hidden' name='data[BoardArticle][parent_field_sort]' value="<?=$db['parent_data']['column']['field_sort']?>">
			<input type='hidden' name='data[BoardArticle][parent_depth]' value="<?=$db['parent_data']['column']['depth']?>">
			<input type='hidden' name='data[BoardArticle][board_category_id]' value="<?=$db['parent_data']['column']['board_category_id']?>">
			<?if($db['parent_data']['column']['secret'] == "Y"){?>
			<input type='hidden' name='data[BoardArticle][secret]' value="Y">
			<?}?>
			<?if($db['parent_data']['column']['public'] == "Y"){?>
			<input type='hidden' name='data[BoardArticle][public]' value="Y">
			<?}?>

			<?if($_board_user['check'] == "YES"){?>
			<input type='hidden' name='data[BoardArticle][owner_id]' value="<?=$_board_user['id']?>">
			<input type='hidden' name='data[BoardArticle][owner_name]' value="<?=$_board_user['name']?>">
			<input type='hidden' name='data[BoardArticle][owner_password]' value="<?=$_board_user['password']?>">
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
				<td><input type='text' name='data[BoardArticle][title]' size='40' value="[RE] <?=ValueNullCheck($db['parent_data']['column']['title'])?>"></td>
			</tr>
			<tr>
				<td>내용</td>
				<td>
					<? echo $fck->fckeditor(array('BoardArticle', 'content'), $html->base.DS.$html->plugin, ValueNullCheck($db['parent_data']['column']['view_content'])); ?>
				</td>
			</tr>

			<?if($_board_user['check'] == "NO"){?>
			<tr>
				<td>작성자 아이디</td>
				<td><input type='text' name='data[BoardArticle][owner_id]' size='20' value=""></td>
			</tr>
			<tr>
				<td>작성자 이름</td>
				<td><input type='text' name='data[BoardArticle][owner_name]' size='20' value=""></td>
			</tr>
			<tr>
				<td>작성자 비밀번호</td>
				<td><input type='password' name='data[BoardArticle][owner_password]' size='20' value=""></td>
			</tr>
			<?}?>

			<?if($db['parent_data']['column']['public'] == "N"){?>
			<tr>
				<td>공개</td>
				<td>
					<input type='radio' name='data[BoardArticle][public]' value='Y'>가
					<input type='radio' name='data[BoardArticle][public]' value='N' checked>부
				</td>
			</tr>
			<?}?>
			<?if($db['parent_data']['column']['secret'] == "N"){?>
			<tr>
				<td>비밀글</td>
				<td>
					<input type='radio' name='data[BoardArticle][secret]' value='Y'>가
					<input type='radio' name='data[BoardArticle][secret]' value='N' checked>부
				</td>
			</tr>
			<?}?>
			<tr>
				<td>제목굴게</td>
				<td>
					<input type='radio' name='data[BoardArticle][subject_bold]' value='Y'>가
					<input type='radio' name='data[BoardArticle][subject_bold]' value='N' checked>부
				</td>
			</tr>

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
						<a href="javascript:_$('DataForm').submit();">[저장]</a>
						<a href='<?=HOSTHOME?>/board/board_article/data_list/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>'>[취소]</a>
					</td>
				</tr>
			</table>