<div class="form-group">
    <div class="form-row">
        <div class="col-md-6">
            <label for="ipserver">Server-IP</label>
            <input class="form-control" id="ipserver" name="add_data[ipserver]" type="text" placeholder="Enter ip server" list="List" onkeypress="return isIP(event)">
            <datalist id="List">
            <?php  foreach($server_sites as $r){ ?>
                <option value="<?php echo $r['server'] ?>" />
            <?php }?>
            </datalist>
        </div>
        <div class="col-md-6">
            <label for="serverport">Server-Port</label>
            <input class="form-control" id="serverport" name="add_data[serverport]" type="text" placeholder="Enter server port" onkeypress="return isNumber(event)">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-row">
        <div class="col-md-6">
            <label for="ipremote">IP-Sim</label>
            <input class="form-control" id="ipremote" name="add_data[ipremote]" placeholder="Enter ip remote"onkeypress="return isIP(event)">
        </div>
        <div class="col-md-6">
            <label for="remoteport">Port-Sim</label>
            <input class="form-control" id="remoteport" name="add_data[remoteport]" placeholder="Enter remote port" onkeypress="return isNumber(event)">
        </div>
    </div>
</div>




<script type="text/javascript">

function isIP(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        return true;
    }else if(charCode < 48 || charCode > 57){
        return false;
    }
    return true;
}//end isIP


function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode < 48 || charCode > 57) {
        return false;
    }
    return true;
}//end isNumber



function success(){

    swal({
        title: "Save already!",
        icon: "success",
        timer: 2000
    })

}//f.success



function warning(inputtab){

    swal({
        title: "warning",
        text: inputtab,
        icon: "warning",
        dangerMode: true,
    })

}//f.warning

</script>