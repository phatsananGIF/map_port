<div class="container-fluid">

    <div class="card card-register mx-auto mt-5">
      <div class="card-header"><i class="fa fa-fw fa-plus"></i> Add new data</div>
      <div class="card-body">
        
        <?php include 'layouts/forminput_view.php';?>

          <div class="text-center">
            <button type="button" name="btsubadd" class="btn btn-success" onclick="save_add()" >Save</button>
            <a class="btn btn-secondary" href="<?=base_url()?>addlist">Cancel</a>
          </div>
        
      </div>
    </div>
    
</div>
<!-- /.container-fluid-->


<script type="text/javascript">

function save_add() {

    var ipserver = document.getElementById("ipserver").value;
    var serverport = document.getElementById("serverport").value;
    var ipremote = document.getElementById("ipremote").value;
    var remoteport = document.getElementById("remoteport").value;
        ipserver = ipserver.trim();
        serverport = serverport.trim();
        ipremote = ipremote.trim();
        remoteport = remoteport.trim();
    
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




</script>