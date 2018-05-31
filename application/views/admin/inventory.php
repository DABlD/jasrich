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
        		<center><h1><b>INVENTORY LIST</b></h1>
        		<hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>
   
        		<!-- <div class="form-inline">
        		<div class="input-group">
        			<form action="<?= base_url() . ucfirst($this->session->role) ?>/searchItem" method="POST">
	    				<input name="search" class="form-control" placeholder="Search for item">
	    				<span class="input-group-btn">
	    				<button class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
	    				</span>
	    			</form>
        		</div>
        		</div> -->
        		<div class="form-inline">
        			<button class="btn btn-warning" data-toggle="modal" data-target="#addModal">ADD ITEM</button>
        			<button class="btn btn-info" data-toggle="modal" data-target="#addModalstock">ADD STOCK</button>
    				<?php if($this->uri->segment(2) != 'deletedItems'): ?>
    			    <a class="btn btn-danger" href="<?= base_url() . ucfirst($this->session->role) . '/deletedItems'?>">Deleted Items</a>
    			    <?php endif; ?>
        		</div>

				<br>
				
		      	<?php
			      	if($this->session->itemAddSuccess)
					{
						echo '<br>';
						echo "<div class='alert alert-success'>";
						echo $this->session->itemAddSuccess;
						echo "</div>";
					}
					elseif($this->session->itemAddFail)
					{
						echo '<br>';
						echo "<div class='alert alert-danger'>";
						echo $this->session->itemAddFail;
						echo "</div>";
						$this->session->unset_userdata('itemAddFail');
					}
			      	elseif($this->session->itemEditSuccess)
					{
						echo '<br>';
						echo "<div class='alert alert-success'>";
						echo $this->session->itemEditSuccess;
						echo "</div>";
					}
					elseif($this->session->itemEditFail)
					{
						echo '<br>';
						echo "<div class='alert alert-danger'>";
						echo $this->session->itemEditFail;
						echo "</div>";	
					}
			      	elseif($this->session->itemDeleteSuccess)
					{
						echo '<br>';
						echo "<div class='alert alert-success'>";
						echo $this->session->itemDeleteSuccess;
						echo "</div>";
					}
					elseif($this->session->itemDeleteFail)
					{
						echo '<br>';
						echo "<div class='alert alert-danger'>";
						echo $this->session->itemDeleteFail;
						echo "</div>";	
					}
					else
					{
						echo '<br>';
					}
				?>	
				<table id="inventoryTables" class="table table-striped table-hover table-bordered table-condensed">
					<thead>
						<tr>
							<td><b>ID</b></td>
							<td><b>NAME</b></td>
							<td><b>CATEGORY</b></td>
							<td><b>STOCK</b></td>
							<td><b>PRICE</b></td>
							<td><b>DATE ADDED</b></td>
							<td><b>ACTION</b></td>
						</tr>
					</thead>

					<tbody>
						<?php foreach($inventory as $item): ?>
						<tr>
							<td width="50px"><?= $item->id ?></td>
							<td><?= $item->name ?></td>
							<td><?= $item->category ?></td>
							<td width="100px"><?= $item->stock ?></td>
							<td width="200px"><?= 'P' . number_format($item->price,2) ?></td>
							<td width="200px"><?= Carbon::parse($item->date_added)->format('F j, Y h:i A') ?></td>
							<td width="150px">
							    <?php if($this->uri->segment(2) != 'deletedItems'): ?>
								<button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?=$item->id?>"><span class="glyphicon glyphicon-trash"></span></button>
								<button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?=$item->id?>"><span class="glyphicon glyphicon-pencil"></span></button>
								<button class="btn btn-success" data-toggle="modal" data-target="#viewModal<?=$item->id?>"><span class="glyphicon glyphicon-search"></span></button>
								<?php else: ?>
								<button class="btn btn-success" data-toggle="tooltip" title="Restore Item" onclick="restore(<?=$item->id?>)"><span class="glyphicon glyphicon-repeat"></span></button>
								<?php endif; ?>
							</td>
						</tr>

						<!-- EDIT MODALS -->
						<div class="modal fade" id="editModal<?=$item->id?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						              <h4 class="modal-title" id="editModalLabel">EDIT ITEM</h4>
						            </div>
						            <div class="modal-body">
						            	<form method="POST" action="<?= base_url() . ucfirst($this->session->role) ?>/doEditItem/<?=$item->id?>">
						      	      	<?php
						      		      	if($this->session->editerrors)
						      				{
						      					$this->session->editerrors;
						      					echo "<div class='alert alert-danger'>";
						      					echo validation_errors();
						      					echo "</div>";
						      				}
						      			?>
						      			<?php if($this->session->editerrors): ?>
						      	      	<div class="form-group">
						      	      	    <label for="name">Name</label>
						      	      	    <input type="text" class="form-control" name="name" placeholder="Enter item name" value="<?=$item->name?>">
						      	  	 	</div>

						      	      	<!-- <div class="form-inline">
			      	      		      		<div class="form-group">
			      	      		      		    <label for="stock">Stock</label>
			      	      	            		<div class="checkbox checkbox-info">
													
													<?php if($item->stock_type == "BOX"): ?>
													<input id="checkbox3" type="checkbox" name="stype" value="box" checked>
													<?php else: ?>
													<input id="checkbox3" type="checkbox" name="stype" value="box">
													<?php endif; ?>

													<label for="checkbox3">
			      	      	            		        BOX
			      	      	            		    </label>
			      	      	            		</div>
			      	      					</div>
			      	      		  	 	</div>

			      	      	      		<div class="form-group">
			      	      		      	    <input type="text" class="form-control" name="stock" placeholder="Enter number of stocks" value="<?=$item->stock?>">
			      	      				</div> -->

						      	      	<div class="form-group">
						      	      	    <label for="price">Price</label>
						      	      	    <input type="text" class="form-control" name="price" placeholder="Enter item price" value="<?= $item->price?>">
						      	  	 	</div>

			      	      				<!-- DATEPICKER -->
			      	      		      	<label>Expiry Date: </label>
			      	      		      	<div id="datepicker1<?=$item->id?>" class="input-group date" data-date-format="mm-dd-yyyy">
			      	      		      	    <input class="form-control" name="expiry" type="text" value="<?=$item->expiry_date?>" readonly>
			      	      		      	    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
			      	      		      	    <span onclick="removeDate1<?=$item->id?>()" class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
			      	      		      	</div>


						      	  	 <?php else: ?>
						      	  	 	<div class="form-group">
						      	      	    <label for="name">Name</label>
						      	      	    <input type="text" class="form-control" name="name" placeholder="Enter item name" value="<?=$item->name?>">
						      	  	 	</div>
						      	      	
						      	      	<!-- <div class="form-inline">
			      	      		      		<div class="form-group">
			      	      		      		    <label for="stock">Stock</label>
			      	      	            		<div class="checkbox checkbox-info">

			      	      	            		  <?php if($item->stock_type == "BOX"): ?>
			      	      	            		  <input id="checkbox3" class="option-input checkbox" type="checkbox" name="stype" value="box" checked>
			      	      	            		  <?php else: ?>
			      	      	            		  <input id="checkbox3" class="option-input checkbox" type="checkbox" name="stype" value="box">
			      	      	            		  <?php endif; ?>

			      	      	            		  <label for="checkbox3">
			      	      	            		      BOX
			      	      	            		  </label>
			      	      	            		</div>
			      	      					</div>
			      	      		  	 	</div>

			      	      	      		<div class="form-group">
			      	      		      	    <input type="text" class="form-control" name="stock" placeholder="Enter number of stocks" value="<?=$item->stock?>">
			      	      				</div> -->

						      	      	<div class="form-group">
						      	      	    <label for="price">Price</label>
						      	      	    <input type="text" class="form-control" name="price" placeholder="Enter item price" value="<?= $item->price?>">
						      	  	 	</div>

			      	      				<!-- DATEPICKER -->
			      	      		      	<label>Expiry Date: </label>
			      	      		      	<div id="datepicker2<?=$item->id?>" class="input-group date" data-date-format="mm-dd-yyyy">
			      	      		      	    <input class="form-control" name="expiry" type="text" value="<?=$item->expiry_date?>" readonly>
			      	      		      	    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
			      	      		      	    <span onclick="removeDate2<?=$item->id?>()" class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
			      	      		      	</div>
						      	  	 <?php endif; ?>
						      	      <div class="modal-footer">
						      	        <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						      	        <button type="submit" class="btn btn-primary">SUBMIT</button>
						      	      </div>
						      	  </form>
						      		</div>
						    </div>
						  </div>
						</div>

						<!-- VIEW MODALS -->
						<div class="modal fade" id="viewModal<?=$item->id?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						              <h4 class="modal-title" id="viewModalLabel">VIEW ITEM</h4>
						            </div>
						            <div class="modal-body">
						            	<form>
						            	<div class="form-group">
						      	      	    <label for="name">Name</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="name" placeholder="Enter item name" value="<?=$item->name?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="stock">Stock</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="stock" placeholder="Enter number of stocks" value="<?=$item->stock?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="price">Price</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="price" placeholder="Enter item price" value="<?= 'P' . number_format($item->price,2) ?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="dadd">Date Added</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="dadd" placeholder="Enter item dadd" value="<?=$item->date_added?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="dup">Date Last Updated</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="dup" placeholder="Enter item dup" value="<?=$item->date_updated?>">
						      	  	 	</div>
						      	      <div class="modal-footer">
						      	        <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						      	      </div>
						      	  </form>
						      		</div>
						    </div>
						  </div>
						</div>
						
						<!-- DELETE MODALS -->
						<div class="modal fade" id="deleteModal<?=$item->id?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						              <h4 class="modal-title" id="deleteModalLabel">ARE YOU SURE TO DELETE ITEM ?</h4>
						            </div>
						            <div class="modal-body">
						            	<form method="POST" action="<?= base_url() . ucfirst($this->session->role) ?>/doDeleteItem/<?=$item->id?>">
						            	<div class="form-group">
						      	      	    <label for="name">Name</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="name" placeholder="Enter item name" value="<?=$item->name?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="stock">Stock</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="stock" placeholder="Enter number of stocks" value="<?=$item->stock?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="price">Price</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="price" placeholder="Enter item price" value="<?=$item->price?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="dadd">Date Added</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="dadd" placeholder="Enter item dadd" value="<?=$item->date_added?>">
						      	  	 	</div>
						      	      	<div class="form-group">
						      	      	    <label for="dup">Date Last Updated</label>
						      	      	    <input disabled="disabled" type="text" class="form-control" name="dup" placeholder="Enter item dup" value="<?=$item->date_updated?>">
						      	  	 	</div>
						      	      <div class="modal-footer">
						      	        <button type="button" class="btn btn-success" data-dismiss="modal">CLOSE</button>
						      	      	<button type="submit" class="btn btn-danger">DELETE</button>
						      	      </div>
						      	  </form>
						      		</div>
						    </div>
						  </div>
						</div>
					<?php endforeach ?>
					</tbody>
				</table>

				<!-- <?= $this->pagination->create_links() ?> -->
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addModalLabel">ADD ITEM</h4>
      </div>
      <div class="modal-body">
      	<form method="POST" action="<?= base_url() ?>Admin/doAddItem">
	      	<?php
		      	if($this->session->errors)
				{
					echo $this->session->errors;
					//$this->session->unset_userdata('errors');

					echo "<div class='alert alert-danger'>";
					echo validation_errors();
					echo "</div>";
				}
			?>
			<?php if($this->session->errors): ?>
	      	<div class="form-group">
	      	    <label for="name">Name</label>
	      	    <input type="text" class="form-control" name="name" placeholder="Enter item name" value="<?= set_value('name') ?>">
	  	 	</div>

	      	<div class="form-group">
	      	    <label for="category">Category</label>
	      	    <select name="category" id="category" class="form-control" style="width: 100%;">
	      	    	<option value="" readonly selected>Select Category</option>
	      	    	<div id="options"></div>
	      	    	<?php foreach($categories as $category): ?>
	      	    		<option value="<?= $category->category?>"><?= $category->category?></option>
	      	    	<?php endforeach; ?>
	      	    </select>
	  	 	</div>

	      	<!-- <div class="form-inline">
	      		<div class="form-group">
	      		    <label for="stock">Stock</label>
            		<div class="checkbox checkbox-info">
            		  <input id="checkbox3" type="checkbox" name="stype" value="box">
            		  <label for="checkbox3">
            		      BOX
            		  </label>
            		</div>
				</div>
	  	 	</div>

      		<div class="form-group">
	      	    <input type="text" class="form-control" name="stock" placeholder="Enter number of stocks" value="<?= set_value('stock') ?>">
			</div> -->


	      	<div class="form-group">
	      	    <label for="price">Price</label>
	      	    <input type="text" class="form-control" name="price" placeholder="Enter item price" value="<?= set_value('price') ?>">
	  	 	</div>

	  	 	<!-- DATEPICKER -->
	      	<label>Expiry Date: </label>
	      	<div id="datepicker3" class="input-group date" data-date-format="mm-dd-yyyy">
	      	    <input class="form-control" name="expiry" type="text" value="<?= set_value('expiry') ?>" readonly>
	      	    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
	      	    <span onclick="removeDate3()" class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
	      	</div>


	  	 <?php else: ?>
	  	 	<div class="form-group">
	      	    <label for="name">Name</label>
	      	    <input type="text" class="form-control" name="name" placeholder="Enter item name">
	  	 	</div>

	      	<div class="form-group">
	      	    <label for="category">Category</label>
	      	    <select name="category" id="category" class="form-control" style="width: 100%;">
	      	    	<option value="" readonly selected>Select Category</option>
	      	    	<?php foreach($categories as $category): ?>
	      	    		<option value="<?= $category->category?>"><?= $category->category?></option>
	      	    	<?php endforeach; ?>
	      	    </select>
	  	 	</div>

	      	<div class="form-group">
	      	    <label for="price">Price</label>
	      	    <input type="text" class="form-control" name="price" placeholder="Enter item price">
	  	 	</div>

	  	 	<!-- DATEPICKER -->
	      	<label>Expiry Date: </label>
	      	<div id="datepicker4" class="input-group date" data-date-format="mm-dd-yyyy">
	      	    <input class="form-control" name="expiry" type="text" readonly>
	      	    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
	      	    <span onclick="removeDate4()" class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
	      	</div>

	  	 <?php endif; ?>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
	        <button type="submit" class="btn btn-primary">SUBMIT</button>
	      </div>
	  </form>
		</div>
    </div>
  </div>
</div>

<div class="modal fade" id="addModalstock" role="dialog" aria-labelledby="addModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="addModalLabel">ADD STOCK</h4>
			</div>
			<div class="modal-body">
				<!-- <form method="POST" action="<?= base_url() ?>Products/addStocks()"> -->
			      	<div class="form-group">
			      	    <label for="name">Item</label>
			      	    <select class="mySelect" id="mySelect" name="name" style="width: 100%" onchange="getPrice()">
		      	    		<option value="">Select Item</option>		
			      	    	<?php foreach($inventory as $item): ?>
			      	    		<option value="<?=$item->id?>"><?=strtoupper($item->name)?></option>
			      	    	<?php endforeach; ?>
			      	    </select>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="qty">Quantity</label>
			      	    <input type="text" class="form-control" name="qty" id="qty" placeholder="Enter Quantity">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="price">Price</label>
			      	    <input type="text" class="form-control" name="price" id="price" placeholder="Enter Price" disabled>
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="supplier">Supplier</label>
			      	    <!-- <input type="text" class="form-control" id="supplier" name="supplier" placeholder="Enter Supplier"> -->
			      	    <select name="suppliers" id="suppliers" class="form-control" style="width: 100%">
			      	    	<option></option>
			      	    </select>
			  	 	</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
						<button type="submit" class="btn btn-primary" onclick="submit()">SUBMIT</button>
					</div>
				<!-- </form> -->
			</div>
		</div>
	</div>
</div>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/cdatatables.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>

<?php 
	if($this->session->editerrors)
	{
		?>
		<script>
			$('#editModal<?=$this->session->editerrors?>').modal('show');
		</script>
		<?php
	}
?>

<script type="text/javascript">

	$(document).ready(function () {
		$('#inventoryTables').DataTable({
			"order": [[0, "desc"]],
			"pageLength": 8
		});

		$('#preloader').fadeOut();

		$.ajax({
			type: 'POST',
			url: '<?= base_url() . $this->session->role . "/getSuppliers"?>',
			success: function(result){
				result = JSON.parse(result);
				size = result.length;

				for(i = 0; i < size; i++)
				{
					$('#suppliers').append('<option value="' + result[i].id + '">' + result[i].text + '</option>');
				}

				$('#suppliers').select2({
					"placeholder": 'Select a supplier',
				});
			}
		})

		$(".mySelect").select2({
		    placeholder: "Select an item",
		});
	});

	function getPrice()
	{
		var id = $('#mySelect').val();

		$.ajax(
		{
			type:'POST',
			data:{id: id},
			url:'<?= base_url()?>Admin/getItemPrice',
			success:function(result)
			{
				$('#price').attr('value', JSON.parse(result).price);
			}
		});
	}

	function submit()
	{
		var name = $('#mySelect').val();
		var qty = $('#qty').val();
		var price = $('#price').val();
		var supplier = $('#suppliers').val();

        if(name == "" || qty == '' || supplier == '')
        {
            swal(
                'Warning',
                'You must fill all fields',
                'warning'
            )
        }
        else
        {
            if(isNaN(qty))
            {
                swal(
                    'Validation error',
                    'You must enter only numbers in Quantity',
                    'warning'
                )
            }
            else
            {
        		$.ajax({
        			type: 'POST',
        			data: {name: name, qty: qty, price: price, supplier: supplier},
        			url: '<?= base_url() . 'Products/addStocks' ?>',
        			success: function(result)
        			{
        				console.log(result);
        				if(result == 1)
        				{
        					swal(
        						'success',
        						'Stock successfully added.',
        						'success'
        					).then(function(){
        						location.reload();
        					})
        				}
        				else
        				{
        					swal(
        						'error',
        						'There was an error updating the stock',
        						'error'
        					)
        				}
        				$('#addModalStock').modal('toggle');
        			}
        		});
            }
        }
	}

    function restore(id)
    {
        swal({
            title: 'Confirmation',
            text: 'Are you sure you want to restore item ?',
            type: 'question',
            showCancelButton: true
        }).then(function(result){
           if(result.value)
           {
               $.ajax({
                    type: 'POST',
                    data: {id: id},
                    url: '<?= base_url() . ucfirst($this->session->role) . "/restore"?>',
                    success: function(result){
                        swal(
                            'Success',
                            'Item successfully restored',
                            'success'
                        ).then(function(){
                           location.reload();
                        });
                    }
               });
           }
        });
    }

	<?php foreach($inventory as $item): ?>
	function removeDate1<?=$item->id?>()
	{
		$('#datepicker1<?=$item->id?>').datepicker('clearDates');
		$('#datepicker1<?=$item->id?>').datepicker('destroy');
		$(function () {
		  $("#datepicker1<?=$item->id?>").datepicker({ 
		        autoclose: true, 
		        todayHighlight: true,
		        startDate: '<?=date('Y-m-d')?>',
		        format: 'yyyy-mm-dd'
		  });
		});
	}
	function removeDate2<?=$item->id?>()
	{
		$('#datepicker2<?=$item->id?>').datepicker('clearDates');
		$('#datepicker2<?=$item->id?>').datepicker('destroy');
		$(function () {
		  $("#datepicker2<?=$item->id?>").datepicker({ 
		        autoclose: true, 
		        todayHighlight: true,
		        startDate: '<?=date('Y-m-d')?>',
		        format: 'yyyy-mm-dd'
		  });
		});
	}
	<?php endforeach ?>

	function removeDate3()
	{
		$('#datepicker3').datepicker('clearDates');
		$('#datepicker3').datepicker('destroy');
		$(function () {
		  $("#datepicker3").datepicker({ 
		        autoclose: true, 
		        todayHighlight: true,
		        startDate: '<?=date('Y-m-d')?>',
		        format: 'yyyy-mm-dd'
		  });
		});
	}
	function removeDate4()
	{
		$('#datepicker4').datepicker('clearDates');
		$('#datepicker4').datepicker('destroy');
		$(function () {
		  $("#datepicker4").datepicker({ 
		        autoclose: true, 
		        todayHighlight: true,
		        startDate: '<?=date('Y-m-d')?>',
		        format: 'yyyy-mm-dd'
		  });
		});
	}
</script>

<script type="text/javascript">

	<?php foreach($inventory as $item): ?>
	$(function () {

	  $("#datepicker1<?=$item->id?>").datepicker({ 
	        autoclose: true, 
	        todayHighlight: true,
	        startDate: '<?=date('Y-m-d')?>',
	        format: 'yyyy-mm-dd'
	  });
	});

	$(function () {
	  $("#datepicker2<?=$item->id?>").datepicker({ 
	        autoclose: true, 
	        todayHighlight: true,
	        startDate: '<?=date('Y-m-d')?>',
	        format: 'yyyy-mm-dd'
	  });
	});
	<?php endforeach ?>

	$(function () {
	  $("#datepicker3").datepicker({ 
	        autoclose: true, 
	        todayHighlight: true,
	        startDate: '<?=date('Y-m-d')?>',
	        format: 'yyyy-mm-dd'
	  });
	});
	$(function () {
	  $("#datepicker4").datepicker({ 
	        autoclose: true, 
	        todayHighlight: true,
	        startDate: '<?=date('Y-m-d')?>',
	        format: 'yyyy-mm-dd'
	  });
	});


	document.title = "Inventory";
</script>