<div class="widget">
    <div class="widget-header">
        <div class="title">
            <i class="fa fa-plus"></i> Add Affiliate
        </div>

    </div>
    <div class="widget-body">
        <div class="row">
            <div class="col-md-8">
                <?php echo form_open(); ?>
                    <?php
                        if(!empty($error)) {
                            echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle"></i> Error:</b>
                            '.$error.'</div>';
                        }
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name:</label>
                                <input type="text" name="first_name" class="form-control" value="<?php echo $_POST['first_name']; ?>" maxlength="35">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name:</label>
                                <input type="text" name="last_name" class="form-control" maxlength="35" value="<?php echo $_POST['last_name']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Email:</label>
                                <input type="text" name="email" class="form-control" maxlength="50" value="<?php echo $_POST['email']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone:</label>
                                <input type="text" name="phone" class="form-control" maxlength="16" value="<?php echo $_POST['phone']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Initial Sign Up Commission %:</label>
                                <?php
                                    if(isset($_POST['signup_commission'])) {
                                        $value = $_POST['signup_commission'];
                                    } else {
                                        $value = 27;
                                    }
                                ?>
                                <input type="text" name="signup_commission" class="form-control" maxlength="2" value="<?php echo $value; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Renewal Commission %:</label>
                                <?php
                                    if(isset($_POST['renewal_commission'])) {
                                        $value = $_POST['renewal_commission'];
                                    } else {
                                        $value = 15;
                                    }
                                ?>
                                <input type="text" name="renewal_commission" class="form-control" maxlength="2" value="<?php echo $value; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Monthly Quota:</label>
                                <?php
                                    if(isset($_POST['monthly_quota'])) {
                                        $value = $_POST['monthly_quota'];
                                    } else {
                                        $value = 21;
                                    }
                                ?>
                                <input type="text" name="monthly_quota" class="form-control" maxlength="2" value="<?php echo $value; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Yearly Bonus %:</label>
                                <?php
                                    if(isset($_POST['yearly_bonus'])) {
                                        $value = $_POST['yearly_bonus'];
                                    } else {
                                        $value = 33;
                                    }
                                ?>
                                <input type="text" name="yearly_bonus" class="form-control" maxlength="2" value="<?php echo $value; ?>" required>
                                <span class="helper">Monthly bonus and yearly bonus should equal 100%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Monthly Bonus %:</label>
                                <?php
                                    if(isset($_POST['monthly_bonus'])) {
                                        $value = $_POST['monthly_bonus'];
                                    } else {
                                        $value = 67;
                                    }
                                ?>
                                <input type="text" name="monthly_bonus" class="form-control" maxlength="2" value="<?php echo $value; ?>" required>
                                <span class="helper">Yearly bonus and monthly bonus should equal 100%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Yearly Quota:</label>
                                <?php
                                    if(isset($_POST['yearly_quota'])) {
                                        $value = $_POST['yearly_quota'];
                                    } else {
                                        $value = 260;
                                    }
                                ?>
                                <input type="text" name="yearly_quota" class="form-control" maxlength="3" value="<?php echo $value; ?>" required>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Affiliate</button>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>