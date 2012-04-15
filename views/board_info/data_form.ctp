

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<? if(IsEmpty($db['data']) == false) {?>
					<td align='center'>게시판 수정</td>
					<?} else {?>
					<td align='center'>게시판 등록</td>
					<?}?>
				</tr>
			</table>


			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_info/data_process'>
			<? if(IsEmpty($db['data']) == false) {?>
			<input type='hidden' name='data[BoardInfo][id]' value="<?=$db['data']['column']['id']?>">
			<?}?>

			<table width='100%' border='1'>
			<col width="150"><col>
			<tr>
				<td>종류</td>
				<td>
					<input type='radio' name='data[BoardInfo][type]' value='A'<?if(ValueIsCheck($db['data']['column']['type'],"A","equal") == true || IsEmpty($db['data']['column']['type']) == true){?> checked<?}?>>일반
					<input type='radio' name='data[BoardInfo][type]' value='B'<?if(ValueIsCheck($db['data']['column']['type'],"B","equal") == true){?> checked<?}?>>이미지
					<input type='radio' name='data[BoardInfo][type]' value='C'<?if(ValueIsCheck($db['data']['column']['type'],"C","equal") == true){?> checked<?}?>>자료
<!--					<input type='radio' name='data[BoardInfo][type]' value='D'<?if(ValueIsCheck($db['data']['column']['type'],"D","equal") == true){?> checked<?}?>>QNA		-->
				</td>
			</tr>
			<tr>
				<td>이름</td>
				<td><input type='text' name='data[BoardInfo][title]' size='20' value="<?=ValueNullCheck($db['data']['column']['title'])?>"></td>
			</tr>
			<tr>
				<td>댓글 사용여부</td>
				<td>
					<input type='radio' name='data[BoardInfo][comment_use]' value='Y'<?if(ValueIsCheck($db['data']['column']['comment_use'],"Y","equal") == true || IsEmpty($db['data']['column']['comment_use']) == true){?> checked<?}?>>사용
					<input type='radio' name='data[BoardInfo][comment_use]' value='N'<?if(ValueIsCheck($db['data']['column']['comment_use'],"N","equal") == true){?> checked<?}?>>비사용
				</td>
			</tr>
			<tr>
				<td>첨부파일 사용여부</td>
				<td>
					<input type='radio' name='data[BoardInfo][file_use]' value='Y'<?if(ValueIsCheck($db['data']['column']['file_use'],"Y","equal") == true){?> checked<?}?>>사용
					<input type='radio' name='data[BoardInfo][file_use]' value='N'<?if(ValueIsCheck($db['data']['column']['file_use'],"N","equal") == true || IsEmpty($db['data']['column']['file_use']) == true){?> checked<?}?>>비사용
				</td>
			</tr>
			<tr>
				<td>썸네일 사용여부</td>
				<td>
					<input type='radio' name='data[BoardInfo][thumbnail_use]' value='Y'<?if(ValueIsCheck($db['data']['column']['thumbnail_use'],"Y","equal") == true){?> checked<?}?>>사용
					<input type='radio' name='data[BoardInfo][thumbnail_use]' value='N'<?if(ValueIsCheck($db['data']['column']['thumbnail_use'],"N","equal") == true || IsEmpty($db['data']['column']['thumbnail_use']) == true){?> checked<?}?>>비사용
				</td>
			</tr>
			<tr>
				<td>답글 사용여부</td>
				<td>
					<input type='radio' name='data[BoardInfo][reply_use]' value='Y'<?if(ValueIsCheck($db['data']['column']['reply_use'],"Y","equal") == true){?> checked<?}?>>사용
					<input type='radio' name='data[BoardInfo][reply_use]' value='N'<?if(ValueIsCheck($db['data']['column']['reply_use'],"N","equal") == true || IsEmpty($db['data']['column']['reply_use']) == true){?> checked<?}?>>비사용
				</td>
			</tr>
			<tr>
				<td>공개여부</td>
				<td>
					<input type='radio' name='data[BoardInfo][public]' value='Y'<?if(ValueIsCheck($db['data']['column']['public'],"Y","equal") == true || IsEmpty($db['data']['column']['public']) == true){?> checked<?}?>>사용
					<input type='radio' name='data[BoardInfo][public]' value='N'<?if(ValueIsCheck($db['data']['column']['public'],"N","equal") == true){?> checked<?}?>>비사용
				</td>
			</tr>
			<tr>
				<td>읽기권한</td>
				<td>
					<input type='radio' name='data[BoardInfo][read]' value='no'<?if(ValueIsCheck($db['data']['column']['read'],"no","equal") == true || IsEmpty($db['data']['column']['read']) == true){?> checked<?}?>>비회원
					<input type='radio' name='data[BoardInfo][read]' value='user'<?if(ValueIsCheck($db['data']['column']['read'],"user","equal") == true){?> checked<?}?>>일반
					<input type='radio' name='data[BoardInfo][read]' value='admin'<?if(ValueIsCheck($db['data']['column']['read'],"admin","equal") == true){?> checked<?}?>>관리자
				</td>
			</tr>
			<tr>
				<td>쓰기권한</td>
				<td>
					<input type='radio' name='data[BoardInfo][write]' value='no'<?if(ValueIsCheck($db['data']['column']['write'],"no","equal") == true || IsEmpty($db['data']['column']['write']) == true){?> checked<?}?>>비회원
					<input type='radio' name='data[BoardInfo][write]' value='user'<?if(ValueIsCheck($db['data']['column']['write'],"user","equal") == true){?> checked<?}?>>일반
					<input type='radio' name='data[BoardInfo][write]' value='admin'<?if(ValueIsCheck($db['data']['column']['write'],"admin","equal") == true){?> checked<?}?>>관리자
				</td>
			</tr>
			<tr>
				<td>댓글권한</td>
				<td>
					<input type='radio' name='data[BoardInfo][comment]' value='no'<?if(ValueIsCheck($db['data']['column']['comment'],"no","equal") == true || IsEmpty($db['data']['column']['comment']) == true){?> checked<?}?>>비회원
					<input type='radio' name='data[BoardInfo][comment]' value='user'<?if(ValueIsCheck($db['data']['column']['comment'],"user","equal") == true){?> checked<?}?>>일반
					<input type='radio' name='data[BoardInfo][comment]' value='admin'<?if(ValueIsCheck($db['data']['column']['comment'],"admin","equal") == true){?> checked<?}?>>관리자
				</td>
			</tr>
			<tr>
				<td>첨부파일개수</td>
				<td>
					<select name='data[BoardInfo][file_ea]'>
						<?for($i=1;$i<=5;$i++){?>
						<option value="<?=$i?>"<?if(ValueIsCheck($db['data']['column']['file_ea'],$i,"equal") == true){?> selected<?}?>><?=$i?>개</option>
						<?}?>
					</select>
				</td>
			</tr>
			<tr>
				<td>첨부파일당최대허용용량</td>
				<td>
					<input type='radio' name='data[BoardInfo][file_size]' value='1048576'<?if(ValueIsCheck($db['data']['column']['file_size'],"1048576","equal") == true || IsEmpty($db['data']['column']['comment']) == true){?> checked<?}?>>1mb
					<input type='radio' name='data[BoardInfo][file_size]' value='2097152'<?if(ValueIsCheck($db['data']['column']['file_size'],"2097152","equal") == true){?> checked<?}?>>2mb
					<input type='radio' name='data[BoardInfo][file_size]' value='3145728'<?if(ValueIsCheck($db['data']['column']['file_size'],"3145728","equal") == true){?> checked<?}?>>3mb
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
						<a href="javascript:document.getElementById('DataForm').submit();">[저장]</a>
						<a href='/pay/board/board_info/data_list'>[취소]</a>
					</td>
				</tr>
			</table>