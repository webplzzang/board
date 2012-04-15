
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'>카테고리 목록</td>
				</tr>
			</table>


			<form name='DataFormDelete' id='DataFormDelete' method='post' action='<?=HOSTHOME?>/board/board_category/data_delete'>
			<input type='hidden' name='data[BoardCategory][id]' id='id'>
			</form>
			<form name='SearchForm' id='SearchForm' action="<?=HOSTHOME?>/board/board_category/data_list" method="POST">
			<input type='hidden' name='data[BoardCategory][data_page]' value='<?php echo ValueNullCheck($parameter['data_page'])?>'>
			<input type='hidden' name='data[BoardCategory][page_per_data_ea]' value='<?php echo ValueNullCheck($parameter['page_per_data_ea'])?>'>
			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='right'>
						<select name='data[BoardCategory][board_info_id]' onchange="_$('SearchForm').submit();">
						<option value="no"<?if(ValueIsCheck($parameter['board_info_id'],"no","equal") == true || IsEmpty($parameter['board_info_id']) == true){?> selected<?}?>>전체</option>
						<?
						for($i=0;$i<sizeof($db['board_info']);$i++)
						{
							$data = $db['board_info'][$i];
						?>
							<option value="<?=$data['column']['id']?>"<?if(ValueIsCheck($parameter['board_info_id'],$data['column']['id'],"equal") == true){?> selected<?}?>><?=$data['column']['title']?></option>
						<?
						}
						?>
						</select>
					</td>
				</tr>
			</table>
			</form>
			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_category/data_fast_process'>
			<input type='hidden' name='data[BoardCategory][prkey_value]' id='prkey_value'>
			<table width='100%' border='1'>
			<col width="30"><col width="50"><col><col width="200"><col width="150"><col width="100">
			<tr>
				<td><input type='checkbox' onclick="allCheck('DataForm','id_check',this);"></td>
				<td>번호</td>
				<td>게시판이름</td>
				<td>카테고리이름</td>
				<td>등록일</td>
				<td>수정/삭제</td>
			</tr>

				<?
				if(sizeof($db['data']['result']) > 0)
				{
					for($i=0;$i<sizeof($db['data']['result']);$i++)
					{
						$data = $db['data']['result'][$i];
				?>
				<tr>
					<td><input type="checkbox" name='id_check' value="<?=$data['column']['id']?>"></td>
					<td><?=$data['virtual_num']?></td>
					<td><?=$data['board_info_title']?></td>
					<td><?=$data['column']['title']?></td>
					<td><?=$data['column']['created']?></td>
					<td>
						<a href="<?=HOSTHOME?>/board/board_category/data_form/<?=$data['column']['id']?>">수정</a><br />
						<a onclick="var chk;chk=confirm('<?=Configure::read('description.confirm_delete')?>');if(chk){_$('DataFormDelete').id.value='<?=$data['column']['id']?>';_$('DataFormDelete').id.value='<?=$data['column']['id']?>';_$('DataFormDelete').submit();}" style='cursor:pointer'>삭제</a><br />
					</td>
				</tr>
				<?
						unset($data);
					}
				}
				else
				{
				?>
				<tr>
					<td colspan='8' height='200'><?=Configure::read('description.no_data')?></td></td>
				</tr>
				<?
				}
				?>

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
						<a href="javascript:dataFastProc('DataForm','id_check');">빠른삭제</a>
						<a href='<?=HOSTHOME?>/board/board_category/data_form'>카테고리등록</a>
					</td>
				</tr>
			</table>