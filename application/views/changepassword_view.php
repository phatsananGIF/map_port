<div class="container-fluid">

    <div class="card card-register mx-auto mt-5">
        <div class="card-header"><i class="fa fa-fw fa-key"></i> Change password</div>
        
        <div class="card-body">

            <?php echo form_open('changepass/index');?>

            <div class="form-group">
                <label for="passoriginal">Old password</label>
                <input id="passoriginal" name="changpass[original]" value="<?php echo set_value('changpass[original]'); ?>" type="password" placeholder="Enter old password" class="form-control input-md" maxlength="8">
                <?php echo form_error('changpass[original]'); ?>
            </div>

            <div class="form-group">
                <label for="newpass">New password</label>
                <input id="newpass" name="changpass[new]" value="<?php echo set_value('changpass[new]'); ?>" type="password" placeholder="Enter new password" class="form-control input-md" maxlength="8">
                <?php echo form_error('changpass[new]'); ?>
                <small id="newpassHelp" class="form-text text-muted">Enter a password of 3-8 characters.</small>
            </div>

            <div class="form-group">
                <label for="connew">Confirm password</label>
                <input id="connew" name="changpass[connew]" value="<?php echo set_value('changpass[connew]'); ?>" type="password" placeholder="Enter confirm password" class="form-control input-md" maxlength="8">
                <?php echo form_error('changpass[connew]'); ?>
            </div>


            <div class="text-center">
                <button type="submit" name="btchangepass" class="btn btn-success" value="Save">Save</button>
                <a class="btn btn-secondary" href="<?=base_url()?>changepass">Cancel</a>
            </div>

            <?php echo form_close();?>
            
        </div>
    </div>
    
</div>
<!-- /.container-fluid-->