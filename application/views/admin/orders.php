<?php
	require 'vendor/autoload.php';
    date_default_timezone_set('Asia/Manila');
	use Carbon\Carbon;
?>

<div id="preloader" style="position: fixed; left: 0; top: 0; z-index: 999; width: 100%; height: 100%; overflow: visible; background: rgba(51,51,51,0.6) url('<?= base_url() . 'img/hourglass.svg'?>') no-repeat center center;"></div>

<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
        	<div style="margin-left: 1%; margin-right: 1%;" class="table-responsive">
        		<center><h1><b>ORDER LIST</b></h1>
        		<hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>
   
        		<div class="form-inline"><button class="btn btn-info" onclick="normalOrBulk()">ADD ORDER</button>
        		<!-- <div class="form-inline"><button class="btn btn-info" data-toggle="modal" data-target="#addModal">ADD ORDER</button> -->
        		
        		<!-- <div class="input-group">
        			<form action="<?= base_url() . $this->session->role ?>/searchOrder" method="POST">
	    				<input name="search" class="form-control" placeholder="Search for order">
	    				<span class="input-group-btn">
	    				<button class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
	    				</span>
	    			</form>
        		</div> -->

	    		<?php if($this->session->role != 'client'): ?>
					<a style="position: absolute; right: 5%;" class="btn btn-primary btn-small" data-toggle="tooltip" title="Transaction List" href="<?= base_url() . $this->session->role ?>/transaction">TRANSACTIONS</a>
				<?php endif; ?>
        		</div>
				
				<br>
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
							<td width="1%;"><b>ID</b></td>
							<td width="15%;"><b>NAME</b></td>
							<td width="3%;"><b>QTY</b></td>
							<td width="2%;"><b>PRICE</b></td>
							<td width="3%;"><b>TOTAL</b></td>
							<td width="10%;"><b>BUYER</b></td>
							<td width="15%;"><b>STATUS</b></td>
							<td width="10%;"><b>ORDER DATE</b></td>
							<td width="5%;"><b>TRANSACTION</b></td>
						</tr>
					</thead>

					<tbody>
						<?php foreach($orders as $order): ?>
						<tr>
							<td><?= $order->id ?></td>
							<td><?= $order->prod_name ?></td>
							<td><?= $order->qty ?></td>
							<td>P<?= number_format($order->prod_price,2) ?></td>
							<td>P<?= number_format($order->qty * $order->prod_price,2); ?></td>
							<td><?= $order->buyer_name ?></td>
							<td>
								<?php if($order->status == 'For Confirmation' && $this->session->role != 'client'): ?>
									<select id="statusChange<?=$order->id?>" style="width: 70%">
											<option disabled readonly selected>For Confirmation</option>
											<option value="confirm">Confirm</option>
											<option value="decline">Decline</option>
											<option value="on_hold">Hold</option>
									</select>
								<a onclick="statusChange(<?=$order->id?>)" class="btn btn-small">OK</a>

								<?php elseif($order->status == 'For Confirmation' && $this->session->role == 'client'): ?>
									<?= $order->status ?>
									<a onclick="statusChange(<?=$order->id?>)" class="btn btn-small" data-toggle="tooltip" title="Cancel Order"><span class="glyphicon glyphicon-remove"></span></a>
									<input type="hidden" id="statusChange<?=$order->id?>" value="cancel">

								<?php elseif($order->status == 'On Hold' && $this->session->role != 'client'): ?>
									<select id="statusChange<?=$order->id?>" style="width: 70%">
											<option disabled readonly selected>On Hold</option>
											<option value="confirm">Confirm</option>
											<option value="decline">Decline</option>
									</select>
									<a style="padding-left: 0px; padding-right: 0px; display: inline;" onclick="getReason('<?=$order->reason?>', '<?= $order->status?>')" class="btn btn-small" data-toggle="tooltip" title="View Reason"><span class="glyphicon glyphicon-search"></span></a>
									<a style="padding-left: 0px; padding-right: 0px; display: inline;" onclick="statusChange(<?=$order->id?>)" class="btn btn-small">OK</span></a>

								<?php elseif($order->status == 'For Delivery' && $this->session->role != 'client'): ?>
									<select id="statusChange<?=$order->id?>" style="width: 70%">
											<option disabled readonly selected>For Delivery</option>
											<option value="complete">Complete</option>
									</select>
								<a onclick="statusChange(<?=$order->id?>)" class="btn btn-small">OK</span></a>
								
								<?php else: ?>
									<?= $order->status == 'cancel' ? 'Cancelled' : $order->status; ?>
									<?php if($order->status == 'Declined' || $order->status == 'On Hold' || $order->status == 'cancel'): ?>
										<a onclick="getReason('<?=$order->reason?>', '<?= $order->status?>')" class="btn btn-small" data-toggle="tooltip" title="View Reason"><span class="glyphicon glyphicon-search"></span></a>
										<?php if($order->status == 'On Hold' && $this->session->role == 'client'): ?>
											<!-- <a onclick="getReason('<?=$order->reason?>', 'cancel')" class="btn btn-small" data-toggle="tooltip" title="Cancel Order"><span class="glyphicon glyphicon-remove"></span></a> -->
											<!-- <input type="hidden" id="statusChange<?=$order->id?>" value="cancel"> -->
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>

							</td>
							<td><?= Carbon::parse($order->date_added)->format('M d, y H:i:s') ?></td>
							<td style="text-align: center;">
								<!-- <button data-toggle="tooltip" title="View Order" class="btn btn-success" data-toggle="modal" data-target="#viewModal<?=$order->id?>"><span class="glyphicon glyphicon-search"></span></button> -->
								<a data-toggle="tooltip" title="View Order Transactions" class="btn btn-warning" href="<?=base_url() . ucfirst($this->session->role) . '/transaction_status/' . $order->id?>"><span class="glyphicon glyphicon-search"></span></a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>

				<!-- <?= $this->pagination->create_links() ?> -->
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" role="dialog" aria-labelledby="addModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="addModalLabel">ADD ORDER</h4>
			</div>
			<div class="modal-body">
				<form method="POST" action="<?= base_url() . $this->session->role ?>/doAddOrder" id="addOrderModal">
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
			      	    <label for="name">Name</label>
			      	    <select class="mySelect" id="mySelect" name="name" style="width: 100%" onchange="getPrice()">
		      	    		<option value="">Select Item</option>		
			      	    	<?php foreach($inventory as $item): ?>
			      	    		<option value="<?=$item->id?>"><?=strtoupper($item->name)?></option>
			      	    	<?php endforeach; ?>
			      	    </select>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="qty" id="addQtyLabel">Quantity</label>
			      	    <input type="text" class="form-control" name="qty" id="qty" onchange="getPrice()" placeholder="Enter Quantity">
			  	 	</div>
					
			  	 	<div class="form-group">
			  	 		<div class="form-inline">
			      	    	<label for="stocksu" style="width: 29%;">Stock</label>
			      	    	<label for="totalsu" style="width: 29%;">Price</label>
			      	    	<label for="totalsu" style="width: 29%;">Total</label>
			      	    </div>
			  	 		<div class="form-inline">
				      	    <input type="text" class="form-control" id="stocksu" placeholder="Stock" disabled style="width: 32.5%;">
				      	    <input type="text" class="form-control" id="pricesu" placeholder="Price" disabled style="width: 32.5%;">
				      	    <input type="text" class="form-control" id="totalsu" placeholder="Total" disabled style="width: 32.5%;">
				      	</div>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="buyer">Hospital</label>
			      	    <input type="text" class="form-control" name="buyer" placeholder="Enter Hospital Name">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="contact">Contact</label>
			      	    <input type="text" class="form-control" name="contact" placeholder="Enter Contact Number">
			  	 	</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						<button type="submit" class="btn btn-primary">SUBMIT</button>
					</div>

					<?php else: ?>
					<div class="form-group">
			      	    <label for="name">Name</label>
			      	    <select class="mySelect" id="mySelect" name="name" style="width: 100%" onchange="getPrice()">
		      	    		<option value="">Select Item</option>		
			      	    	<?php foreach($inventory as $item): ?>
			      	    		<?php if($name['name'] == $item->name): ?>
			      	    			<option value="<?=$item->id?>" selected><?=strtoupper($item->name)?></option>
			      	    		<?php else: ?>
			      	    			<option value="<?=$item->id?>"><?=strtoupper($item->name)?></option>
			      	    		<?php endif ?>	
			      	    	<?php endforeach; ?>
			      	    </select>
			  	 	</div>


			  	 	<div class="form-group">
			      	    <label for="qty">Quantity</label>
			      	    <input type="text" class="form-control" name="qty" id="qty" onchange="getPrice()" placeholder="Enter Quantity" value="<?= set_value('qty') ?>">
			  	 	</div>

			  	 	<div class="form-group">
			  	 		<div class="form-inline">
			      	    	<label for="stocksu" style="width: 29%;">Stock</label>
			      	    	<label for="pricesu" style="width: 29%;">Price</label>
			      	    	<label for="totalsu" style="width: 29%;">Total</label>
			      	    </div>
			  	 		<div class="form-inline">
				      	    <input type="text" class="form-control" id="stocksu" placeholder="Stock" disabled style="width: 32.5%;">
				      	    <input type="text" class="form-control" id="pricesu" placeholder="Price" disabled style="width: 32.5%;">
				      	    <input type="text" class="form-control" id="totalsu" placeholder="Total" disabled style="width: 32.5%;">
				      	</div>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="buyer">Hospital</label>
			      	    <input type="text" class="form-control" name="buyer" placeholder="Enter Hospital Name" value="<?= set_value('buyer') ?>">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="contact">Contact</label>
			      	    <input type="text" class="form-control" name="contact" placeholder="Enter Contact Email/Number" value="<?= set_value('contact') ?>">
			  	 	</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						<button type="submit" class="btn btn-primary">SUBMIT</button>
					</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/cdatatables.js"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/moment.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/numeral.js"></script>

<?php  
	if($this->session->validation_error)
	{
		echo "<script>$('#addModal').modal('show');</script>";
	}
?>

<script type="text/javascript">

    var valid = 0;
    var type;

	$('[id^="statusChange"]').select2({
		placeholder: 'For Confirmation'
	});
	
	$('#addOrderModal').submit(function(e){
	   getPrice();
	   
	   if(!valid)
	   {
	   		e.preventDefault();
	   }
	    // $('#addOrderModal').submit();
	});

	function normalOrBulk()
	{
		swal({
			title: 'Normal or Bulk order ?',
			type: 'question',
			showCancelButton: true,
			cancelButtonText: 'Bulk',
			confirmButtonText: 'Normal',
			cancelButtonColor: '#d2011b',
		}).then(function(result){
			if(result.value)
			{
				type = 'normal';
			}
			else if(result.dismiss == 'cancel')
			{
				type = 'bulk';
			}

			if(result.dismiss != 'overlay')
			{
				$('#addModal').modal('toggle');
			}
		})
	}

	function getReason(reason, type)
	{
		if(type == 'Declined')
		{
			swal(
				'Declined',
				'The admin declined this order because ' + reason,
				'info'
			)
		}
		else if(type == 'cancel')
		{
			swal(
				'Cancelled',
				'The client cancelled this order because ' + reason,
				'info'
			)
		}
		else
		{
			swal(
				'On Hold',
				'The admin hold this order because ' + reason,
				'info'
			)
		}
	}

	function statusChange(id)
	{
		var status = $('#statusChange' + id).val();
		if(status == null)
		{
			swal(
				'Warning',
				'You have not change the status of that order',
				'warning'
			)
		}
		else
		{
			if(status == 'decline' || status == 'on_hold' || status == 'cancel')
			{
				swal({
				  title: 'Enter Reason',
				  input: 'text',
				  showCancelButton: true,
				  confirmButtonText: 'Submit',
				  showLoaderOnConfirm: true,
				  preConfirm: (reason) => {
				    return new Promise((resolve) => {
				      setTimeout(() => {
				        if (reason == '') {
				          swal.showValidationError(
				            'Just give me a reason.'
				          )
				        }
				        else
				        {
				        	$.ajax({
				        		type: 'POST',
				        		url: '<?= base_url() . ucfirst($this->session->role) ?>/statusDecline',
				        		data: {id: id, reason: reason, status: status},
				        		success: function(result)
				        		{
				        			if(result == 1)
				        			{
				        				swal(
				        					'Warning',
				        					'The order has been declined.',
				        					'success'
				        				).then(function(){
				        					location.reload();
				        				});
				        			}
				        			else
				        			{
				        				swal(
				        					'Error',
				        					'There was a problem updating the order. Please Try Again.',
				        					'error'
				        				).then(function(){
				        					location.reload();
				        				});
				        			}
				        		}
				        	});	
				        }
				        resolve()
				      }, 1000)
				    })
				  },
				  allowOutsideClick: () => !swal.isLoading()
				})
			}
			else if(status == 'complete'){
				$.ajax({
			    	type: 'POST',
			    	url: '<?= base_url() . ucfirst($this->session->role) ?>/statusComplete',
			    	data: {id: id},
			    	success: function(result)
			    	{
			    		if(result == 1)
			    		{
			    			swal(
			    				'Success',
			    				'Order Has Been Completed !',
			    				'success'
			    			).then(function(){
			    				location.reload();
			    			});
			    		}
			    		else
			    		{
			    			swal(
			    				'Error',
			    				'There was a problem completing the order. Please Try Again.',
			    				'error'
			    			).then(function(){
			    				location.reload();
			    			});
			    		}
			    	}
			    });
			}
			else
			{
				$.ajax({
			    	type: 'POST',
			    	url: '<?= base_url() . ucfirst($this->session->role) ?>/statusChange',
			    	data: {id: id},
			    	success: function(result)
			    	{
			    		if(result == 1)
			    		{
			    			swal(
			    				'Success',
			    				'Order Has Been Confirmed !',
			    				'success'
			    			).then(function(){
			    				location.reload();
			    			});
			    		}
			    		else
			    		{
			    			swal(
			    				'Error',
			    				'There was a problem confirming the order. Please Try Again.',
			    				'error'
			    			).then(function(){
			    				location.reload();
			    			});
			    		}
			    	}
			    });
			}
		}
	}

	function getPrice()
	{
		var id = $('#mySelect').val();
		var qty = $('#qty').val();
		if(type == 'bulk' && qty < 200)
		{
			swal(
				'Bulk order has a minimum of 200 quantity',
				'',
				'warning',
			)
		}
		else
		{
			$.ajax(
			{
				type:'POST',
				data:{id: id},
				url:'<?= base_url() . ucfirst($this->session->role) ?>/getItemPrice',
				success:function(result)
				{
				    if(result != 'null')
				    {
	    				var res = JSON.parse(result);
	    				$('#stocksu').attr('value', res.stock);
	    				$('#pricesu').attr('placeholder', res.price);
	    
	    				if(qty <= parseInt(res.stock) && qty != "")
	    				{
	    					$('#totalsu').attr('placeholder', numeral(res.price * qty).format('0,0.00'));
	    					valid = 1;
	    				}
	    				else if(qty > parseInt(res.stock))
	    				{
	    					swal({
	    					  title: 'Error',
	    					  text:'Not Enough Stocks !',
	    					  type: 'error',
	    					  timer: 1000
	    					})
	    					$('#qty').val('');
	    					$('#totalsu').val('');
	    					valid = 0;
	    				}
				    }
				    else
				    {
				        swal(
				           'Warning',
				           'You must fill all details',
				           'warning'
				        )
				        valid = 0;
				    }
				}
			});
		}
	}
</script>

<script type="text/javascript">
	document.title = "Orders";

	$(document).ready(function() {
	  	$(".mySelect").select2({
		    placeholder: "Select an item",
		});

		$('#orderTables').DataTable({
			"order": [[0, "desc"]],
			"pageLength": 8
		});

		$('#preloader').fadeOut();

		// table.column(8).data().sort();
	    // table.api().column(7).order('desc').draw();
	});
</script>