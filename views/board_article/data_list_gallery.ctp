
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'><?=$db['board_info']['column']['title']?> 목록1</td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='right' style='padding-top:10px'>
						<?if(ValueIsCheck($parameter['view_type'],"gallery","equal") == true){?>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=gallery&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>"><b>[앨범형]</b></a>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=board&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>">[게시판형]</a>
						<?}?>
						<?if(ValueIsCheck($parameter['view_type'],"board","equal") == true || IsEmpty($parameter['view_type']) == true){?>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=gallery&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>">[앨범형]</a>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=board&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>"><b>[게시판형]</b></a>
						<?}?>
					</td>
				</tr>
			</table>

			<form name='GoArtcleForm' id='GoArtcleForm' method='post' action='<?=HOSTHOME?>/board/board_article/data_form'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' id='board_info_id' value="<?=$parameter['board_info_id']?>">
			<input type='hidden' name='data[BoardArticle][board_category_id]' id='board_category_id' value="<?=ValueNullCheck($parameter['board_category_id'])?>">
			<input type='hidden' name='data[BoardArticle][data_page]' value='<?php echo ValueNullCheck($parameter['data_page'])?>'>
			<input type='hidden' name='data[BoardArticle][page_per_data_ea]' value='<?php echo ValueNullCheck($parameter['page_per_data_ea'])?>'>
			<input type='hidden' name='data[BoardArticle][search_method]' value='<?php echo ValueNullCheck($parameter['search_method'])?>'>
			<input type='hidden' name='data[BoardArticle][search_keyword]' value='<?php echo ValueNullCheck($parameter['search_keyword'])?>'>
			<input type='hidden' name='data[BoardArticle][view_type]' value='<?php echo ValueNullCheck($parameter['view_type'])?>'>
			</form>
			<form name='DataFormDelete' id='DataFormDelete' method='post' action='<?=HOSTHOME?>/board/board_article/data_delete'>
			<input type='hidden' name='data[BoardArticle][id]' id='id'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' id='board_info_id' value="<?=$parameter['board_info_id']?>">
			<input type='hidden' name='data[BoardArticle][board_category_id]' id='board_category_id' value="<?=ValueNullCheck($parameter['board_category_id'])?>">
			</form>
			<form name='SearchForm' id='SearchForm' action="<?=HOSTHOME?>/board/board_article/data_list" method="POST">
			<input type='hidden' name='data[BoardArticle][view_type]' value='<?php echo ValueNullCheck($parameter['view_type'])?>'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' value='<?php echo ValueNullCheck($parameter['board_info_id'])?>'>
			<input type='hidden' name='data[BoardArticle][data_page]' value='<?php echo ValueNullCheck($parameter['data_page'])?>'>
			<input type='hidden' name='data[BoardArticle][page_per_data_ea]' value='<?php echo ValueNullCheck($parameter['page_per_data_ea'])?>'>

			<?if(IsEmpty($db['board_category']) == false){?>
			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='right'>
						카테고리 : 
						<select name='data[BoardArticle][board_category_id]' onchange="_$('SearchForm').submit();">
						<option value="no"<?if(ValueIsCheck($parameter['board_category_id'],"no","equal") == true || IsEmpty($parameter['board_category_id']) == true){?> selected<?}?>>전체</option>
						<?
						for($i=0;$i<sizeof($db['board_category']);$i++)
						{
							$data = $db['board_category'][$i];
						?>
							<option value="<?=$data['column']['id']?>"<?if(ValueIsCheck($parameter['board_category_id'],$data['column']['id'],"equal") == true){?> selected<?}?>><?=$data['column']['title']?></option>
						<?
						}
						?>
						</select>

						검색종류 : 
						<select name='data[BoardArticle][search_method]'>
							<option value='no'<?if(ValueIsCheck($parameter['search_method'],"no","equal") == true || IsEmpty($parameter['search_method']) == true){?> selected<?}?>>전체</option>
							<option value="title"<?if(ValueIsCheck($parameter['search_method'],"title","equal") == true){?> selected<?}?>>제목</option>
							<option value="content"<?if(ValueIsCheck($parameter['search_method'],"content","equal") == true){?> selected<?}?>>내용</option>
							<option value="all"<?if(ValueIsCheck($parameter['search_method'],"both","equal") == true){?> selected<?}?>>제목 + 내용</option>
						</select>

						검색어 : 
						<input type='text' name='data[BoardArticle][search_keyword]' size="20" value="<?php echo ValueNullCheck($parameter['search_keyword'])?>">

						<input type='submit' value='검색'>
					</td>
				</tr>
			</table>
			<?}?>

			</form>
			<form name='DataForm' id='DataForm' method='post' action='/admin/board_article/data_fast_process'>
			<input type='hidden' name='data[BoardArticle][mode]' id='mode'>
			<input type='hidden' name='data[BoardArticle][prkey_value]' id='prkey_value'>
			<input type='hidden' name='data[BoardArticle][change_col_name]' id='change_col_name'>
			<input type='hidden' name='data[BoardArticle][change_col_value]' id='change_col_value'>
			<table width='100%' border='1'>
			<col width="20%"><col width="20%"><col width="20%"><col width="20%"><col width="20%">

				<?
				if(sizeof($db['data']['result']) > 0)
				{
					for($i=0;$i<sizeof($db['data']['result']);$i++)
					{
						$data = $db['data']['result'][$i];
						if($i % 5 == 0)
						{
				?>
				<tr>
				<?
						}
				?>

					<td>
				<?
				if(IsEmpty(AddFileImageCheck($data['file'])) == false)
				{
				?>
					<img src="<?=HOSTHOME?>/board/board_file/image/<?=AddFileImageCheck($data['file'],"path")?>">
				<?
				}
				?>	
						<br />
						<a href="<?=HOSTHOME?>/board/board_article/data_view/<?=$data['column']['id']?>/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>"<?if($data['column']['subject_bold'] == "Y"){echo " style='font-weight:bold'";}?>>
						<?=$data['space']." ".$data['re']?>
						<?if(IsEmpty($data['column']['board_category_id']) == false && ValueIsCheck($data['column']['board_category_id'],"0","not") == true) echo "[".$data['category']['column']['title']."]";?>
						<?=$data['column']['title']?>
						<?if($data['column']['ment_count'] > 0) echo "(".$data['column']['ment_count'].")";?>
						<?if(sizeof($data['file']) > 0) echo "[파일]";?>
						<?if($data['column']['secret'] == "Y") echo "[비밀글]";?>
						</a>
					</td>

				<?
						if($i % 5 == 4 || $i == (sizeof($db['data']['result']) - 1))
						{
							if($i % 5 < 4)
							{
								for($j=0;$j<(4 - ($i % 5));$j++)
								{
									echo "<td></td>";
								}
							}
				?>
				</tr>
				<?
						}
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
					<td height='10'>
						<?
						if($db['data']['search_total_page'] > 1)
						{
							$paginator->options = array('url'=>array('?' =>ViewParameterParsing($parameter,"board_info_id,board_category_id,view_type")));
							echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled'))."&nbsp;";
							echo $this->Paginator->numbers()."&nbsp;";
							echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled'));
							//echo $this->Paginator->counter();
						}
						?>
					</td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>
						<a href="javascript:_$('GoArtcleForm').submit();">게시글 등록</a>
					</td>
				</tr>
			</table>