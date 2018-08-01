<div class="form-group">
    <div class="form-row">

        <div class="col-md-6">
            <label for="username">User name</label>
            <input class="form-control" id="username" name="add_data[username]" type="text" placeholder="Enter user name" >
        </div>
        
        <div class="col-md-6">
            <label for="password">Password</label>
            <input class="form-control" id="password" name="add_data[password]" type="password" placeholder="Enter password" maxlength="8" >
            <small id="newpassHelp" class="form-text text-muted">Enter a password of 3-8 characters.</small>
        </div>

    </div>
</div>

<div class="form-group">
    <div class="form-row">
       
        <div class="col-md-6">
            <label for="typeuser">User type</label>
            <select class="form-control" id="typeuser" name="add_data[typeuser]">

            </select>
        </div>

    </div>
</div>




<script type="text/javascript">


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