<div class="container-fluid">
   

      <!-- Icon Cards-->
      <div class="row" id="IconCards" >
      <?php foreach($server_sites as $site){ ?>

        <div class="col-xl-3 col-sm-6 mb-3">
          <div class="card text-white bg-secondary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon"><i class="fa fa-fw fa-server"></i></div>
              <div class="mr-5"><?= $site['server'] ?></div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="#" onclick="listserver('<?= $site['server'] ?>')">
              <span class="float-left">Value <span servercount="<?= $site['server'] ?>" ><?= $site['count'] ?></span> view list</span>
              <span class="float-right"><i class="fa fa-angle-down"></i></span>
            </a>
          </div>
        </div>

      <?php } ?>
      </div><!-- Icon Cards-->


      <!-- Area Cards -->
      <div class="card mb-3">
        <div class="card-header" id="H-card" >
            <i class="fa fa-server"></i> Server <span id="ipserver_title" ></span>
            <span id="btaddnew" class="float-right"></span>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            
            <table class="table table-striped table-bordered" id="tb-showlist" style="width:100%" cellspacing="0">

                <thead>
                    <tr>
                        <th style="text-align:center;">NO.</th>
                        <th style="text-align:center;">Server</th>
                        <th style="text-align:center;">Port</th>
                        <th style="text-align:center;">Ip-Sim</th>
                        <th style="text-align:center;">Port-Sim</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Command</th>
                    </tr>
                </thead>
              
                <tbody>

                </tbody>

            </table>

          </div>            
        </div>



        <!-- Modal -->
        <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <?php include 'layouts/forminput_view.php';?>
                
            </div>
            <div class="modal-footer" id="modelBt" >
                <button type="button" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

            <div class="list-group list-group-flush small" id="listgroup">
                <div class="list-group-item">
                    <div class="media">
                        <div class="media-body">
                            <div><strong>Port in IP sim</strong> </div>
                            <div id="detail" class="form-row"></div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
        </div><!-- Modal -->

      </div><!-- End Area Cards -->

    
</div>
<!-- /.container-fluid-->


<script type="text/javascript">

function listserver(server_ip){
    console.log(server_ip);
    var fnadd = "popup_addnew('"+server_ip+"')";
    var btaddnew = '<a href="#" onclick="'+fnadd+'" ><i class="fa fa-plus"></i></a >';

  $('#tb-showlist').DataTable().clear().destroy();

    dataI = {"server_ip":server_ip};
    console.log(dataI);
    $.ajax({
        type:"POST",
        url:"<?php echo base_url(); ?>home/getlist",
        cache:false,
        dataType:"JSON",
        data:dataI,
        async:true,
        beforeSend: function() {
          showPleaseWait();
        },
        success:function(result){
            console.log(result);
            $("span[servercount = '"+server_ip+"']").html(result.data.length);
            $("#ipserver_title").html(server_ip);
            $("#btaddnew").html(btaddnew);

            DataTableshowlist(result.data);

            hidePleaseWait();
            
        }//end success
    });//end $.ajax 

}//end f.listserver



function popup_addnew(server_ip){

    var btsave = '<button type="button" class="btn btn-success" onclick="save_add()" >Save</button>';
        btsave += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
    document.getElementById("modelBt").innerHTML = btsave;
    document.getElementById("listgroup").style.display = "none";

    document.getElementById("formModalLabel").innerHTML = "Add new data";
    document.getElementById("ipserver").value = server_ip;
    document.getElementById("ipserver").readOnly = true;
    document.getElementById("serverport").value = null;
    document.getElementById("ipremote").value = null;
    document.getElementById("ipremote").readOnly = false;
    document.getElementById("remoteport").value = null;

    $("#formModal").modal("show");

}//end f.popup_addnew



function save_add() {

    var ipserver = document.getElementById("ipserver").value;
    var serverport = document.getElementById("serverport").value;
    var ipremote = document.getElementById("ipremote").value;
    var remoteport = document.getElementById("remoteport").value;
    ipserver.trim(); serverport.trim(); ipremote.trim(); remoteport.trim();

    if(ipserver == ""){
        warning("please enter ip server");
        return false;
    }else if(serverport == ""){
        warning("please enter server port");
        return false;
    }else if(ipremote == ""){
        warning("please enter ip sim");
        return false;
    }else if(remoteport == ""){
        warning("please enter sim port");
        return false;
    } 

    dataI={"ipserver":ipserver, "serverport":serverport, "ipremote":ipremote, "remoteport":remoteport};
    $.ajax({
        type:"POST",
        url:"<?php echo base_url(); ?>home/saveadd",
        cache:false,
        dataType:"JSON",
        data:dataI,
        async:true,
        success:function(result){
            if(result != 'save'){
                    warning(result);
                    return false;
            }
            success();
            listserver(ipserver);

        }//end success
    });//end $.ajax 


}//f.save_add



function popup_edit(id){

    var btsave = '<button type="button" class="btn btn-success" onclick="save_edit('+id+')" >Save</button>';
        btsave += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
    document.getElementById("modelBt").innerHTML = btsave;
    document.getElementById("formModalLabel").innerHTML = "Edit data";
    document.getElementById("listgroup").style.display = "block";


    dataI = {"id":id};
    $.ajax({
        type:"POST",
        url:"<?php echo base_url(); ?>home/getbyID",
        cache:false,
        dataType:"JSON",
        data:dataI,
        async:true,
        success:function(result){

            console.log(result);

            document.getElementById("ipserver").value = result.listbyID.serverIp;
            document.getElementById("ipserver").readOnly = true;
            document.getElementById("serverport").value = result.listbyID.serverPort;
            document.getElementById("ipremote").value = result.listbyID.remoteIp;
            document.getElementById("ipremote").readOnly = true;
            document.getElementById("remoteport").value = result.listbyID.remotePort;
            
            var dataresult = "";
            $.each( result.listportremote, function(i,data) {
                dataresult += "<div class='col-md-2'>"+data.remotePort+"</div>";
            });
            document.getElementById("detail").innerHTML = dataresult;
            
        }//end success
    });//end $.ajax 


    $("#formModal").modal("show");

    
}//end f.popup_addnew



function save_edit(id){

    console.log('id => '+id);

    var ipserver = document.getElementById("ipserver").value;
    var serverport = document.getElementById("serverport").value;
    var ipremote = document.getElementById("ipremote").value;
    var remoteport = document.getElementById("remoteport").value;
    ipserver.trim(); serverport.trim(); ipremote.trim(); remoteport.trim();

    if(ipserver == ""){
        warning("please enter ip server");
        return false;
    }else if(serverport == ""){
        warning("please enter server port");
        return false;
    }else if(ipremote == ""){
        warning("please enter ip sim");
        return false;
    }else if(remoteport == ""){
        warning("please enter sim port");
        return false;
    } 

    dataI={"id":id, "ipserver":ipserver, "serverport":serverport, "ipremote":ipremote, "remoteport":remoteport};
    $.ajax({
        type:"POST",
        url:"<?php echo base_url(); ?>home/save_edit",
        cache:false,
        dataType:"JSON",
        data:dataI,
        async:true,
        success:function(result){
            if(result != 'save'){
                    warning(result);
                    return false;
            }
            success();
            listserver(ipserver);

        }//end success
    });//end $.ajax 
    
    
}//end f.popup_addnew



function dellist(id){

    swal({
        title: "Are you sure deleted ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $('#tb-showlist').DataTable().clear().destroy();

            dataI = {"id":id};
            $.ajax({
                type:"POST",
                url:"<?php echo base_url(); ?>home/del",
                cache:false,
                dataType:"JSON",
                data:dataI,
                async:true,
                beforeSend: function() {
                    showPleaseWait();
                },
                success:function(result){

                    listserver(result.serverIp);
                    
                }//end success
            });//end $.ajax 

        } else {
            return false;
        }
    });



}//end f.dellist



function DataTableshowlist(datalist){

    var showlist = $('#tb-showlist').DataTable( {
        data: datalist,
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var oSettings = $('#tb-showlist').dataTable().fnSettings();
            $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
            $(nRow).attr("id",'row_' + aData.id);
            return nRow;
        },
        pageLength: 10,
        lengthMenu: [
            [ 10, 100, 1000 ],
            [ '10', '100', '1,000' ]
        ],
        order: [[ 5, "desc" ]],
        
    } );//end DataTable

}//end f.DataTableshowlist



/**
 * Displays overlay with "Please wait" text. Based on bootstrap modal. Contains animated progress bar.
 */
function showPleaseWait() {
    var modalLoading = '<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false" role="dialog">\
        <div class="modal-dialog">\
            <div class="modal-content">\
                <div class="modal-header">\
                    <h4 class="modal-title">Please wait...</h4>\
                </div>\
                <div class="modal-body">\
                    <div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>';
    $(document.body).append(modalLoading);
    $("#pleaseWaitDialog").modal("show");
}


/**
 * Hides "Please wait" overlay. See function showPleaseWait().
 */
function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}

</script>