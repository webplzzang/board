
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'>비밀번호 확인</td>
				</tr>
			</table>


			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_article/confirm_data_process'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' value="<?=ValueNullCheck($parameter['board_info_id'])?>">
			<input type='hidden' name='data[BoardArticle][board_category_id]' value="<?=ValueNullCheck($parameter['board_category_id'])?>">
			<input type='hidden' name='data[BoardArticle][id]' value="<?=ValueNullCheck($parameter['id'])?>">
			<input type='hidden' name='data[BoardArticle][action]' value="<?=ValueNullCheck($parameter['action'])?>">
			<table width='100%' border='1'>
			<col width="150"><col>
			<tr>
				<td>비밀번호</td>
				<td><input type='text' name='data[BoardArticle][confirm_password]' size='40' value=""></td>
			</tr>

			</form>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td height='10'></td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>
						<a href="javascript:_$('DataForm').submit();">[확인]</a>
						<a href='<?=HOSTHOME?>/board/board_article/data_list/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>'>[취소]</a>
					</td>
				</tr>
			</table>