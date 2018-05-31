<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
        	<div style="margin-left: 10%; margin-right: 10%;" class="table-responsive">
        		<center><h1><b>PURCHASE ORDER</b></h1>
        		<hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>				

				<?php 
					if($this->session->orderAddSuccess)
					{
						echo '<br>';
						echo "<div class='alert alert-success'>";
						echo $this->session->orderAddSuccess;
						echo "</div>";
					}
					elseif($this->session->orderAddFail)
					{
						echo '<br>';
						echo "<div class='alert alert-danger'>";
						echo $this->session->orderAddFail;
						echo "</div>";	
					}
				?>

				<table id="orderTables" class="table table-striped table-hover table-bordered table-condensed">
					<thead>
						<tr>
							<td><b>ID</b></td>
							<td><b>ORDER</b></td>
							<td><b>AMOUNT PAID</b></td>
							<td><b>DATE</b></td>
						</tr>
					</thead>

					<tbody>
						<?php $amountPaid = 0;?>
						<?php $total = 0;?>
						<?php if(count($transaction) == 0): ?>
							<tr>
								<td width="50px"></td>
								<td width="200px">NO PAYMENTS MADE</td>
								<td width="100px">-</td>
								<td width="100px"></td>
							</tr>
						<?php endif ?>
						<?php foreach($transaction as $data): ?>
						<tr>
							<td width="50px"><?= $data->t_id ?></td>
							<td width="200px"><?= $data->prod_name ?></td>
							<td width="100px">P<?= $data->amount_paid ?></td>
							<td width="100px"><?= $data->date ?></td>
						</tr>
						<?php $total =$data->prod_price * $data->qty;?>
						<?php $amountPaid = $amountPaid + $data->amount_paid?>
						<?php endforeach ?>
						<tr>
							<td width="50px"></td>
							<td width="200px"><b>TOTAL</b></td>
							<td width="100px" id="total1">P<?=$amountPaid?></td>
							<td width="100px"></td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td width="200px"><b>PAYABLE</b></td>
							<td width="100px" id="total2">P<?=$total?></td>
							<td width="100px"></td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td width="200px"><b>BALANCE</b></td>
							<td width="100px" id="total3">P<?php if($total - $amountPaid < 0){echo 0;}else{echo $total- $amountPaid;}?></td>
							<td width="100px"></td>
						</tr>
					</tbody>
				</table>

				<?= $this->pagination->create_links() ?>
			</div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>


<?php if(count($transaction) == 0): ?>
<script type="text/javascript">
	function noPayments(id)
	{
		$.ajax({
			type: 'POST',
			data: {id: id},
			url: '<?= base_url() . 'Admin/getBalance'?>',
			success: function(result)
			{
				// alert(result);
				$('#total1').html('-');
				$('#total2').html('P' + result);
				$('#total3').html('P' + result);
			}
		});
	}

	noPayments('<?=$id?>');
</script>
<?php endif ?>

<script type="text/javascript">
	document.title = "Orders";

	$(document).ready(function() {
	  	$(".mySelect").select2({
		    placeholder: "Select an item",
		});
	});
</script>