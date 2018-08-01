<div class="container-fluid">

    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-fw fa-users"></i> Manageuser
            <span class="float-right"><a href="#" onclick="popup_addnewuser()" ><i class="fa fa-plus"></i></a ></span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tb-showlist" style="width:100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>User name</th>
                            <th>User type</th>
                            <th>Status</th>
                            <th>Command</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div> 
        </div>

    </div><!-- /.card mb-3-->

    <!-- Modal -->
    <div class="modal fade" id="formModaluser" tabindex="-1" role="dialog" aria-labelledby="formModaluser_Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModaluser_Label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <?php include 'layouts/forminputuser_view.php';?>
                
            </div>
            <div class="modal-footer" id="modelBt" >
                <button type="button" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
    </div><!-- Modal -->

</div><!-- /.container-fluid-->

<script type="text/javascript">

    $(document).ready(function(){
        var userlogin = <?php echo json_encode($server_sites); ?>;
        DataTableshowlist(userlogin.datauser);

    });//end document.ready


    function popup_addnewuser(){

        document.getElementById("formModaluser_Label").innerHTML = "Add new user";
        document.getElementById("username").value = null;
        document.getElementById("username").readOnly = false;
        document.getElementById("password").value = null;

        var typeuser = <?php echo json_encode($server_sites); ?>;
        var listselect = '';
        typeuser.type_user.forEach(function(element) {
            listselect += '<option value="'+element.type_user_id+'">'+element.type_user_name+'</option>';
        });
        document.getElementById("typeuser").innerHTML = listselect;

        var btsave = '<button type="button" class="btn btn-success" onclick="save_add()" >Save</button>';
        btsave += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
        document.getElementById("modelBt").innerHTML = btsave;
        
        $("#formModaluser").modal("show");

    }//end f.popup_addnewuser


    function save_add(){
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;
        var typeuser = document.getElementById("typeuser").value;
        username.trim(); password.trim();

        if(username == ""){
            warning("please enter user name");
            return false;
        }else if(password == ""){
            warning("please enter password");
            return false;
        }else if(password.length < 3){
            warning("please enter a password of 3-8 characters.");
            return false;
        }

        dataI={"username":username, "password":password, "typeuser":typeuser };
        $.ajax({
            type:"POST",
            url:"<?php echo base_url(); ?>manageuser/saveadd",
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
                location.reload();

            }//end success
        });//end $.ajax 

    }//end f.save_add


    function popup_edituser(id){

        dataI = {"id":id};
        $.ajax({
            type:"POST",
            url:"<?php echo base_url(); ?>manageuser/getuserbyID",
            cache:false,
            dataType:"JSON",
            data:dataI,
            async:true,
            success:function(result){
                
                document.getElementById("formModaluser_Label").innerHTML = "Edit user";
                document.getElementById("username").value = result.user_name;
                document.getElementById("username").readOnly = true;
                document.getElementById("password").value = null;

                var typeuser = <?php echo json_encode($server_sites); ?>;
                var listselect = '';
                typeuser.type_user.forEach(function(element) {
                    var selected = '';
                    if(element.type_user_id == result.user_type){
                        selected = 'selected';
                    }
                    listselect += '<option value="'+element.type_user_id+'" '+selected+' >'+element.type_user_name+'</option>';
                });
                document.getElementById("typeuser").innerHTML = listselect;

                var btsave = '<button type="button" class="btn btn-success" onclick="save_edit('+id+')" >Save</button>';
                btsave += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                document.getElementById("modelBt").innerHTML = btsave;
                
                $("#formModaluser").modal("show");
                        
            }//end success
        });//end $.ajax 

    }//end f.popup_edituser



    function save_edit(id){
        console.log(id);

        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;
        var typeuser = document.getElementById("typeuser").value;
        password.trim();

        if(password.length != 0 && password.length < 3){
            warning("please enter a password of 3-8 characters.");
            return false;
        }

        dataI={"id":id, "username":username, "password":password, "typeuser":typeuser };
        $.ajax({
            type:"POST",
            url:"<?php echo base_url(); ?>manageuser/saveedit",
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
                location.reload();

            }//end success
        });//end $.ajax 


    }//end f.save_edit



    function deluser(id){
        console.log(id);
        swal({
        title: "Are you sure deleted ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {

                window.location.href = '<?php echo base_url(); ?>manageuser/deluser/'+id;

            } else {
                return false;
            }
        });
    }//end f.deluser



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
            order: [[ 3, "desc" ]],
            
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