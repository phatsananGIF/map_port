<div class="container-fluid">
    <?php if($showtab=="alladd"){
        $one_active = "";
        $one_showactive = "";
        $all_active = "active";
        $all_showactive = "show active";
    }else{
        $all_active = "";
        $all_showactive = "";
        $one_active = "active";
        $one_showactive = "show active";
    } ?>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?=$one_active?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Add one</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?=$all_active?>" id="addall-tab" data-toggle="tab" href="#addall" role="tab" aria-controls="addall" aria-selected="false">Add all</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
    
        <div class="tab-pane fade <?=$one_showactive?>" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card card-register mx-auto mt-5" >
                <div class="card-header"><i class="fa fa-fw fa-pencil-square-o"></i> Add new data</div>
                <div class="card-body">
                
                <?php include "layouts/forminput_view.php";?>

                    <div class="text-center">
                    <button type="button" name="btsubadd" class="btn btn-success" onclick="save_add()" >Save</button>
                    <a class="btn btn-secondary" href="<?=base_url()?>addlist">Cancel</a>
                    </div>
                
                </div>
            </div>
        </div>

        <div class="tab-pane fade <?=$all_showactive?>" id="addall" role="tabpanel" aria-labelledby="addall-tab">
            <div class="card card-register mx-auto mt-5" style="margin-bottom: 1rem" >
                <div class="card-header"><i class="fa fa-fw fa-download"></i> Add new data</div>
                <div class="card-body">
                    
                    <form action="<?=base_url()?>addlist" method="post" enctype="multipart/form-data" >
                        
                        Select files: <input type="file" name="file" >
                        <br><br>
                        <input type="submit" name="btsubmit" class="btn btn-success" value="Save" />

                    </form>
                
                </div>
            </div>

            <!-- Area Cards -->
            <div class="card mb-3">
                <div class="card-header" id="H-card" >
                    <i class="fa fa-list-alt"></i> List upload
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
                            </tr>
                        </thead>
                    
                        <tbody>

                        </tbody>

                    </table>

                </div>            
                </div>


            </div><!-- End Area Cards -->

        </div>
        
    </div>
    
</div>
<!-- /.container-fluid-->

<script type="text/javascript">

$(document).ready(function(){
    <?php if(form_error('file') =='<span class="badge badge-danger">Please choose a file.</span>'){ ?>
            
        warning("Please choose a file.");
        
    <?php }elseif(form_error('file') =='<span class="badge badge-danger">Please select only text file.</span>'){ ?>

        warning("Please select only text file.");

    <?php } ?>

    var result = <?php echo json_encode($datafile); ?>;
    console.log(result);
    $('#tb-showlist').DataTable().clear().destroy();
    DataTableshowlist(result);

});//document.ready




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
        url:"<?php echo base_url(); ?>addlist/saveadd",
        cache:false,
        dataType:"JSON",
        data:dataI,
        async:true,
        success:function(result){
           console.log(result);
           if(result != 'save'){
                warning(result);
                return false;
           }
           success();

        }//end success
    });//end $.ajax 


    
}//f.save_add



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
    
} );//end DataTable

}//end f.DataTableshowlist


</script>