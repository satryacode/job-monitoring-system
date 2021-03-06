<script>
$(document).ready(function() {
	$("#table-jo-detail").css('opacity', 0).animate({opacity:1}, 800);
	$(".datetime-input").datepicker({dateFormat:"dd/mm/yy", autoOpen : false, changeMonth : true, changeYear : true});
	$( "#comment-box" ).dialog({
	      autoOpen: false,
	      height: "auto",
	      width: 650,
	      modal: true,
	      position: "center",
	      open: function(){
				$(this).closest(".ui-dialog").find(".ui-dialog-titlebar:first").hide();
		      }
	    });
	$(".comment").click(function(){
		$("#comment-box").dialog("open");
		var post = {};
		post['id_detail_jo_activity'] = $(this).next().val();
		$("#comment-wrapper").load('<?php echo base_url('/jo/load_comment')?>',post,function(str){
				
			});
        });
	 $("#table-jo-detail tfoot tr:not(.accordion)").hide();
	 $("#table-jo-detail tfoot tr:first-child").show();
	 $("#table-jo-detail tfoot tr.accordion").click(function(){
	 $(this).nextAll("tr").fadeToggle();
	    });
});
</script>

<input type="hidden" id="division-val" value="<?php echo $current_division?>" >
<table id="table-jo-detail" class="tinytable" style="margin-top:10px;margin-bottom:10px;float:left;">
		<thead>
			<tr style="height: 20px;">
				<th style="padding: 5px;">
					Activities
				</th>
				<th style="padding: 5px;">
					Plan
				</th>
				<th style="padding: 5px;">
					Actual
				</th>
				<th style="padding: 5px;">
					Status
				</th>
				<th style="padding: 5px;">
					Comment
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$input_status = '';
			$input_status_admin = '';
			if($myclass->authorize_admin_user() || $myclass->authorize_division_user('4'))
			{
				$input_status_admin = 'datetime-input';
				$input_status = 'datetime-input';
			}
			
			if($myclass->authorize_division_user($current_division))
			{
				$input_status = 'datetime-input';
			}
			
			foreach($division_activity as $divact)
			{
				$plan = (empty($divact['plan'])) ? '' : date('d/m/Y', strtotime($divact['plan']));
				$actual = (empty($divact['actual'])) ? '' : date('d/m/Y', strtotime($divact['actual']));
			?>
			<tr>
				<td>
				<?php echo $divact['name'];?>
				</td>
				<td style="text-align: center;width: 50px;">
					<input type="text" <?php echo 'class="' . $input_status_admin . '"';?> value="<?php echo $plan;?>" readonly/>
				</td>
				<td style="text-align: center; width: 50px;">
					<input type="text" <?php echo 'class="' . $input_status . '"';?> value="<?php echo $actual;?>" readonly/>
				</td>
				<td style="text-align: center; width: 10px;">
					<img src="<?php echo base_url('/images/' . $myclass->get_status_activity($divact['plan'], $divact['actual']) . '.png');?>">
				</td>
				<td style="width: 150px;">
					<?php 
					$comments = $jo_model->get_comment_detail($divact['id_detail_jo_activity']);
					if(count($comments) > 0)
					{
						echo substr($comments[0]['comment'], 0, 15) . '...';
					}
					?>
					<a href="#" class="comment" style="float: right; text-decoration: underline; color: orange;"><b>
					<?php 
					if(count($comments) > 0)
					{
						echo '('. count($comments) .')';
					}
					else
					{
						echo '(add)';
					}
					?>
					</b></a><input type="hidden" value="<?php echo $divact['id_detail_jo_activity'];?>" >
				</td>
				<input type="hidden" value="<?php echo $divact['id_detail_jo_activity']; ?>" />
			</tr>
			<?php
			}
			?>
		</tbody>
		<tfoot>
			<tr class="accordion">
				<td colspan="5" style="text-align: center; vertical-align: middle;">
					Product Detail
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<table style="width:100%;">
						<tr>
							<th style="padding: 5px;">
								Product Name
							</th>
							<th style="padding: 5px;">
								Quantity
							</th>
						</tr>
						<?php 
						foreach($jo_product as $pro)
						{?>
							<tbody>
								<tr>
									<td>
										<?php echo $pro['name'];?>
									</td>
									<td>
										<?php echo $pro['quantity'];?>
									</td>
								</tr>
							</tbody>
						<?php 
						}
						?>
					</table>
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<?php 
	$this->load->view('dashboard/part/comment_activity');
	?>