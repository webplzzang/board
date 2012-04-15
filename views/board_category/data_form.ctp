

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<? if(IsEmpty($db['data']) == false) {?>
					<td align='center'>카테고리 수정</td>
					<?} else {?>
					<td align='center'>카테고리 등록</td>
					<?}?>
				</tr>
			</table>


			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_category/data_process'>
			<? if(IsEmpty($db['data']) == false) {?>
			<input type='hidden' name='data[BoardCategory][id]' value="<?=$db['data']['column']['id']?>">
			<?}?>

			<table width='100%' border='1'>
			<col width="150"><col>
			<tr>
				<td>게시판</td>
				<td>
					<select name='data[BoardCategory][board_info_id]'>
					<?
					for($i=0;$i<sizeof($db['board_info']);$i++)
					{
						$data = $db['board_info'][$i];
					?>
						<option value="<?=$data['column']['id']?>"<?if(ValueIsCheck($db['data']['column']['board_info_id'],$data['column']['id'],"equal") == true){?> selected<?}?>><?=$data['column']['title']?></option>
					<?
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>이름</td>
				<td><input type='text' name='data[BoardCategory][title]' size='20' value="<?=ValueNullCheck($db['data']['column']['title'])?>"></td>
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
						<a href="javascript:document.getElementById('DataForm').submit();">[저장]</a>
						<a href='/pay/board/board_category/data_list'>[취소]</a>
					</td>
				</tr>
			</table>