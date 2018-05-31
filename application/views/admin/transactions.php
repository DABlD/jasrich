<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
        	<div style="margin-left: 10%; margin-right: 10%;" class="table-responsive">
        		<center><h1><b>TRANSACTION LIST</b></h1>
        		<hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>
   
        		<div class="form-inline"><button class="btn btn-info" data-toggle="modal" data-target="#addModal">ADD TRANSACTION</button>
        		
        		<div class="input-group">
        			<form action="<?= base_url() ?>Admin/searchTransaction" method="POST">
	    				<input name="search" class="form-control" placeholder="Search for transaction">
	    				<span class="input-group-btn">
	    				<button class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
	    				</span>
	    			</form>
        		</div>
        		</div>
				
				<br>

				<?php 
					if($this->session->transactionAddSuccess)
					{
						echo '<br>';
						echo "<div class='alert alert-success'>";
						echo $this->session->transactionAddSuccess;
						echo "</div>";
					}
					elseif($this->session->transactionAddFail)
					{
						echo '<br>';
						echo "<div class='alert alert-danger'>";
						echo $this->session->transactionAddFail;
						echo "</div>";	
					}
				?>
				<table id="transactionTable" class="table table-striped table-hover table-bordered table-condensed">
					<thead>
						<tr>
							<td><b>ID</b></td>
							<td><b>ORDER</b></td>
							<td><b>BUYER</b></td>
							<td><b>AMOUNT PAID</b></td>
							<td><b>DATE</b></td>
							<!-- <td><b>ACTION</b></td> -->
						</tr>
					</thead>

					<tbody>
						<?php foreach($transactions as $transaction): ?>
						<tr>
							<td width="50px"><?= $transaction->t_id ?></td>
							<td width="200px"><?= $transaction->prod_name?></td>
							<td width="200px"><?= $transaction->buyer_name?></td>
							<td width="100px">P<?= number_format($transaction->amount_paid,2) ?></td>
							<td width="100px"><?= $transaction->date ?></td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>

				<?= $this->pagination->create_links() ?>
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" role="dialog" aria-labelledby="addModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="addModalLabel">ADD TRANSACTION</h4>
			</div>
			<div class="modal-body">
				<form method="POST" action="<?= base_url() ?>Admin/doAddTransaction" id="form1">
			      	<?php
				      	if($this->session->validation_error)
						{
							$this->session->validation_error;
							//$this->session->unset_userdata('validation-error');

							echo "<div class='alert alert-danger'>";
							echo validation_errors();
							echo "</div>";
						}
					?>
					
					<?php if(!$this->session->validation_error): ?>
			      	<div class="form-group">
			      	    <label for="name">Order</label>
							<select class="mySelect" id="mySelect" name="order" style="width: 100%" onchange="showBalance()">
		      	    		<option value="">Select Order</option>		
			      	    	<?php foreach($orders as $order): ?>
			      	    		<option value="<?=$order->id?>"><?=strtoupper($order->prod_name) . ' - ' . $order->buyer_name?></option>
			      	    	<?php endforeach; ?>
			      	    </select>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="balance">Balance</label>
			      	    <input type="text" class="form-control" id="temp" name="temp" placeholder="Order Balance" disabled>
			      	    <input type="text" id="balance" hidden>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="amount">Amount</label>
			      	    <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Amount">
			  	 	</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						<a class="btn btn-primary" onclick="checkPayment()">SUBMIT</a>
					</div>

					<?php else: ?>
					<div class="form-group">
			      	    <label for="name">Order</label>
							<select class="mySelect" id="mySelect" name="order" style="width: 100%" onchange="showBalance()">
		      	    		<option value="">Select Order</option>		
			      	    	<?php foreach($orders as $order): ?>
		      	    			<option value="<?=$order->id?>"><?=strtoupper($order->prod_name) . ' - ' . $order->buyer_name?></option>
			      	    	<?php endforeach; ?>
			      	    </select>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="balance">Balance</label>
			      	    <input type="text" class="form-control" id="balance" name="balance" placeholder="Order Balance" disabled>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="amount">Amount</label>
			      	    <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Amount" value="<?= set_value('amount') ?>">
			  	 	</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						<a class="btn btn-primary" onclick="checkPayment()">SUBMIT</a>
					</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/numeral.js"></script>

<?php  
	if($this->session->validation_error)
	{
		echo "<script>$('#addModal').modal('show');</script>";
	}
?>

<script type="text/javascript">
	function checkPayment()
	{
		var balance = parseFloat(parseFloat($('#balance').val()).toFixed(2));
		var payment = parseFloat(parseFloat($('#amount').val()).toFixed(2));

		if(balance != "" && payment > balance)
		{
			swal(
				'Warning',
				'The payment is over the amount of balance',
				'warning'
			)
		}
		else
		{
			if(balance == payment && payment > 0)
			{
				var order_id = $('#mySelect').val();
				$.ajax({
					type: 'POST',
					data: {id: order_id},
					url: '<?= base_url() . "Admin/for_delivery"?>',
					success: function(result)
					{
						$('#form1').submit();
					}
				});
			}
			else
			{
				$('#form1').submit();
			}
		}
	}

	function showBalance()
	{
		var id = document.getElementById('mySelect').value;
		$.ajax({
			type: 'POST',
			url: '<?= base_url() . "Admin/getBalance"?>',
			data: {id: id},
			success: function(result)
			{
				$('#balance').attr('value', result);
				$('#temp').attr('value', 'P' + numeral(result).format('0,0.00'));
			}
		});
	}

</script>

<script type="text/javascript">
	document.title = "Transactions";

	$(document).ready(function() {
	  	$(".mySelect").select2({
		    placeholder: "Select order",
		});
	});
</script>