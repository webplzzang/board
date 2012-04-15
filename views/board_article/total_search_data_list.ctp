
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'> 통합 검색 </td>
				</tr>
			</table>


			<form name='GoArtcleForm' id='GoArtcleForm' method='post' action='<?=HOSTHOME?>/board/board_article/data_form'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' id='board_info_id' value="">
			<input type='hidden' name='data[BoardArticle][board_category_id]' id='board_category_id' value="">
			<input type='hidden' name='data[BoardArticle][data_page]' value=''>
			<input type='hidden' name='data[BoardArticle][page_per_data_ea]' value=''>
			<input type='hidden' name='data[BoardArticle][search_method]' value=''>
			<input type='hidden' name='data[BoardArticle][search_keyword]' value=''>
			<input type='hidden' name='data[BoardArticle][view_type]' value=''>
			</form>


			<table width='100%' border='1'>
			<col width="150"><col><col width="100"><col width="150">
			<tr>
				<td>게시판이름</td>
				<td>제목</td>
				<td>작성자</td>
				<td>등록일</td>
			</tr>

				<?
				if(sizeof($db['data']['result']) > 0)
				{
					for($i=0;$i<sizeof($db['data']['result']);$i++)
					{
						$data = $db['data']['result'][$i];
				?>
				<tr>
					<td><?=$data['board']['column']['title']?></td>
					<td align='left'>
						<a href="<?=HOSTHOME?>/board/board_article/data_view/<?=$data['column']['id']?>/page:1?board_info_id=<?=$data['column']['board_info_id']?>&board_category_id=<?=$data['column']['board_category_id']?>"<?if($data['column']['subject_bold'] == "Y"){echo " style='font-weight:bold'";}?>>
						<?=$data['space']." ".$data['re']?>
						<?=$data['column']['title']?>
						<?if($data['column']['secret'] == "Y") echo "[비밀글]";?>
						</a>
					</td>
					<td><?=ValueNullCheck($data['column']['owner_name'])?></td>
					<td><?=date("Y-m-d",$data['column']['write'])?></td>
				</tr>
				<?
						unset($data);
					}
				}
				else
				{
				?>
				<tr>
					<td colspan='4' height='200'><?=Configure::read('description.no_data')?></td></td>
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