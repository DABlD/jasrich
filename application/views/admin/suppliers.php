<?php
	require 'vendor/autoload.php';
    date_default_timezone_set('Asia/Manila');
	use Carbon\Carbon;
?>

<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
        	<div style="margin-left: 1%; margin-right: 1%;" class="table-responsive">
        		<center><h1><b>SUPPLIER LIST</b></h1>
        		<hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>
   
        		<div class="form-inline"><button class="btn btn-info" data-toggle="modal" data-target="#addModal">ADD SUPPLIER</button>
        		
        <!--		<div class="input-group">-->
        <!--			<form action="<?= base_url() ?>Admin/searchTransaction" method="POST">-->
	    			<!--	<input name="search" class="form-control" placeholder="Search for transaction">-->
	    			<!--	<span class="input-group-btn">-->
	    			<!--	<button class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>-->
	    			<!--	</span>-->
	    			<!--</form>-->
        <!--		</div>-->
        		<!--</div>-->
				
				<br>
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
				<table id="supplierTable" class="table table-striped table-hover table-bordered table-condensed">
					<thead>
						<tr>
							<td><b>ID</b></td>
							<td><b>NAME</b></td>
							<td><b>LOCATION</b></td>
							<td><b>CONTACT</b></td>
							<td><b>ADDED ON</b></td>
							<td><b>ACTIVE</b></td>
							<td><b>ACTION</b></td>
						</tr>
					</thead>

					<tbody>
						<?php foreach($suppliers as $supplier): ?>
						<tr>
							<td width="50px"><?= $supplier->id ?></td>
							<td width="200px"><?= $supplier->name?></td>
							<td width="200px"><?= $supplier->location?></td>
							<td width="200px"><?= $supplier->contact?></td>
							<td width="200px"><?= Carbon::parse($supplier->date_added)->format('F j, Y h:i A')?></td>
							<td width="200px"><?= $supplier->deleted_at == null ? 'Yes': 'No'?></td>
							<td width="50px">
							    <?php if($supplier->deleted_at == null): ?>
								<button class="btn btn-danger" data-toggle="tooltip" title="Disable supplier" onclick="disable(<?= $supplier->id?>)"><span class="glyphicon glyphicon-remove" ></span></button>
                                <?php else: ?>
<button class="btn btn-success" data-toggle="tooltip" title="Restore Supplier" onclick="restore(<?=$supplier->id?>)"><span class="glyphicon glyphicon-repeat"></span></button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" role="dialog" aria-labelledby="addModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="addModalLabel">Add Supplier</h4>
			</div>
			<div class="modal-body">
				<form method="POST" action="<?= base_url() ?>Admin/addSupplier" id="form1">
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
			      	    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="location">Location</label>
			      	    <input type="text" class="form-control" name="location" id="location" placeholder="Enter Location">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="contact">Contact</label>
			      	    <input type="text" class="form-control" name="contact" id="contact" placeholder="Enter Contact">
			  	 	</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						<button class="btn btn-primary">SUBMIT</button>
					</div>

					<?php else: ?>
					
			  	 	<div class="form-group">
			      	    <label for="name">Name</label>
			      	    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="<?= set_value('name')?>">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="location">Location</label>
			      	    <input type="text" class="form-control" name="location" id="location" placeholder="Enter Location" value="<?= set_value('location')?>">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="contact">Contact</label>
			      	    <input type="text" class="form-control" name="contact" id="contact" placeholder="Enter Contact" value="<?= set_value('contact')?>">
			  	 	</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						<button class="btn btn-primary">SUBMIT</button>
					</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/cdatatables.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/numeral.js"></script>

<script type="text/javascript">

    <?php if($this->session->validation_error): ?>
        $('#addModal').modal('show');
    <?php endif; ?>

    $(document).ready(function(){
    	$('#supplierTable').DataTable({
			"order": [[0, "desc"]],
			"pageLength": 8
    	});
    })

	function disable(id)
	{
	    
        swal({
            title: 'Confirmation',
            text: 'Are you sure you want to disable supplier ?',
            type: 'question',
            showCancelButton: true
        }).then(function(result){
           if(result.value)
           {
               $.ajax({
                    type: 'POST',
                    data: {id: id, type: 'disable'},
                    url: '<?= base_url() . ucfirst($this->session->role) . "/toggleSupplier"?>',
                    success: function(result){
                        swal(
                            'Success',
                            'Supplier successfully disabled',
                            'success'
                        ).then(function(){
                           location.reload();
                        });
                    }
               });
           }
        });
	    
	}
	
	function restore(id)
	{    
        swal({
            title: 'Confirmation',
            text: 'Are you sure you want to enable Supplier ?',
            type: 'question',
            showCancelButton: true
        }).then(function(result){
           if(result.value)
           {
               $.ajax({
                    type: 'POST',
                    data: {id: id, type: 'restore'},
                    url: '<?= base_url() . ucfirst($this->session->role) . "/toggleSupplier"?>',
                    success: function(result){
                        swal(
                            'Success',
                            'Supplier successfully enabled',
                            'success'
                        ).then(function(){
                           location.reload();
                        });
                    }
               });
           }
        });
	}

</script>

<script type="text/javascript">
	document.title = "Suppliers";
</script>