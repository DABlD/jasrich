<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
        	<div style="margin-left: 1%; margin-right: 1%;" class="table-responsive">
        		<center><h1><b>STOCK LOGS</b></h1>
        		<hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>				
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
				?>

				<table id="inventory" class="table table-striped table-bordered table-hover">
				   <thead>
				       <tr>
				           <th style="background-color: #ebe6e6;">ITEM ID</th>
				           <th style="background-color: #ebe6e6;">NAME</th>
				           <th style="background-color: #ebe6e6;">STOCKS</th>
				           <th style="background-color: #ebe6e6;">IN/OUT</th>
				           <th style="background-color: #ebe6e6;">FROM/TO</th>
				           <th style="background-color: #ebe6e6;">DATE</th>
				       </tr>
				   </thead>
				   <tbody></tbody>
				</table>

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
	      	    	<option value=""></option>
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
	      	    	<option value=""></option>
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
			      	    <input type="text" class="form-control" id="supplier" name="supplier" placeholder="Enter Supplier">
			  	 	</div>

			  	 	<div class="form-group">
			      	    <label for="contact">Contact</label>
			      	    <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter Contact Email/Number">
			  	 	</div>

					<div class="modal-footer">
						<!-- <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button> -->
						<button type="submit" class="btn btn-primary" onclick="submit()">SUBMIT</button>
					</div>
				<!-- </form> -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script> -->
<!-- <script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>

<script type="text/javascript">
</script>

<script type="text/javascript">
	document.title = "Stock Logs";
	$('#category').select2({
		placeholder: 'Select Category'
	});

	<?php 
		if($this->session->errors)
		{
			echo "<script>$('#addModal').modal('show');</script>";
		}
	?>

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
		var supplier = $('#supplier').val();
		var contact = $('#contact').val();

        if(name == "" || qty == '' || supplier == '' || contact == '')
        {
            swal(
                'Warning',
                'You must fill all fields',
                'warning'
            )
        }
        else
        {
            if(isNaN(contact))
            {
                swal(
                    'Validation error',
                    'You must enter only numbers in Contact',
                    'warning'
                )
            }
            else if(isNaN(qty))
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
        			data: {name: name, qty: qty, price: price, supplier: supplier, contact: contact},
        			url: '<?= base_url() . 'Products/addStocks' ?>',
        			success: function(result)
        			{
        				if(result == 1)
        				{
        					swal(
        						'success',
        						'Stock successfully added.',
        						'success'
        					)
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

	$(document).ready(function () {
	    var oTable = $("#inventory").dataTable({
	        "bProcessing": true,
	        "sAjaxSource": "<?= base_url() . 'Products/getProductLogs' ?>",
	        "sPaginationType": "full_numbers",
	        "columns": [
	            { "data": "id", "name": "business.id" },
	            { "data": "name", "name": "business.name" },
	            { "data": "qty", "name": "business.qty" },
	            { "data": "way", "name": "business.way"},
	            { "data": "toFrom" },
	            { "data": "date" },
	        ],
	        "fnServerData": function (sSource, aoData, fnCallback)
	        {
	            $.ajax
	            ({
	                "dataType": "json",
	                "type": "POST",
	                "url": sSource,
	                "data": aoData,
	                "success": fnCallback
	            });
	        },
	    });

	    oTable.api().column(5).order('desc').draw();
	});

	$(".mySelect").select2({
	    placeholder: "Select an item",
	});
</script>