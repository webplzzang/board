
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'>첨부파일</td>
				</tr>
			</table>


			<form name='DataForm' id='DataForm' method='post' enctype="multipart/form-data"  action='<?=HOSTHOME?>/board/board_file/http_upload_process'>
			<input type='hidden' name='data[BoardFile][pt]' value="<?=ValueNullCheck($parameter['pt'])?>">
			<input type='hidden' name='data[BoardFile][thumb]' value="<?=ValueNullCheck($parameter['thumb'])?>">
			<input type="hidden" name="MAX_FILE_SIZE" value="<?=ValueNullCheck($parameter['file_size'])?>" />
			<table width='100%' border='1'>
			<col width="150"><col>
			<tr>
				<td>파일</td>
				<td><input type='file' name='data[BoardFile][add]' id='add' size='40' value=""></td>
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
						<a href="javascript:if(_$('DataForm').add.value==''){alert('파일을 선택하세요!');}else{_$('DataForm').submit();}">[확인]</a>
						<a href='javascript:window.close();'>[취소]</a>
					</td>
				</tr>
			</table>