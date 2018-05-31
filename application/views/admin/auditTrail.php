<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
            <div style="margin-left: 1%; margin-right: 1%;" class="table-responsive">
                <center><h1><b>AUDIT TRAIL</b></h1>
                <hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>
   
                <div class="form-inline">
                    <!-- <button class="btn btn-warning btn-small" style="position: absolute; right: 5%;" id="auditButton" onclick="auditButton()">Audit Trail</button> -->
                </div>
                <br>
                <div>

                  <table id="auditTrail" class="table table-striped table-bordered table-hover">
                     <thead>
                         <tr>
                             <th style="background-color: #ebe6e6;">ITEM ID</th>
                             <th style="background-color: #ebe6e6;">NAME</th>
                             <th style="background-color: #ebe6e6;">STOCKS</th>
                             <th style="background-color: #ebe6e6;">IN/OUT</th>
                         </tr>
                     </thead>
                     <tbody></tbody>
                  </table>
                </div>

                <br>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/moment.js"></script>



<script type="text/javascript">
    $(document).ready(function () {
        var oTable = $("#auditTrail").dataTable({
            "bProcessing": true,
            "sAjaxSource": "<?= base_url() . 'Reports/getAuditTrail' ?>",
            "sPaginationType": "full_numbers",
            "columns": [
                { "data": "id", "name": "audit_trail.id" },
                { "data": "username", "name": "audit_trail.name" },
                { "data": "action", "name": "audit_trail.qty" },
                { "data": "date_added", "name": "audit_trail.way"},
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

        oTable.api().column(3).order('desc').draw();
    });
</script>

<script type="text/javascript">
    document.title = "Audit Trail";
    // $('#type').select2({
    //     placeholder: 'Select Sales',
    // });
    // $("#date").css("height", $("#select2-type-container").height());
    // $("#getButton").css("height", $("#select2-type-container").height());
</script>