<div class="widget">
    <div class="widget-header">
        <div class="title">
            <i class="fa fa-laptop"></i> Website Settings
        </div>
    </div>
    <div class="widget-body">
        <div class="row">
            <div class="col-md-8">
                <?php
				
                    if(!empty($error)) {
                        echo '<div class="alert alert-danger"><b><i class="fa fa-times"></i> Error:</b>
                        '.$error['error'].'</div>';
                    }
					
					$success = $this->session->flashdata('success');
                    if(!empty($success)) {
                        echo '<div class="alert alert-success"><b><i class="fa fa-times"></i> Success:</b>
                            '.$this->session->flashdata('success').'</div>';
                    }
                    echo form_open_multipart('affiliates/my-website/save/',
                        array('class'=>'form-horizontal row-border'));
                ?>
                    <h3><i class="fa fa-gears"></i> Page Settings</h3>

                    <div class="form-group">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span> Unique Name</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="text" name="unique_name" class="form-control"
                                   value="<?php
                                    if(property_exists($page, 'unique_name')) {
                                        echo $page->unique_name; };
                                   ?>"
                                   maxlength="35" placeholder="Unique Name" required>
                            <span class="help-block">
                                This must be unique. http://n4rlocal.com/<b>unique-name-here</b>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Profile Image/Logo</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="file" name="profile" class="form-control">
                            <span class="help-block">
                                Image should be at least 500x500
                         </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Background Image</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="file" name="background" class="form-control">
                             <span class="help-block">
                                Image should be at least 500x500
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            <span class="text-danger">*</span> Bio/Description:</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <textarea name="desc" class="form-control" required maxlength="500"
                                      style="min-height:150px"><?php
                                if(property_exists($page, 'desc')) {
                                    echo $page->desc; };
                                ?></textarea>
                            <span class="help-block">
                                500 Characters Max
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <hr>
                        </div>
                    </div>

                    <h3><i class="fa fa-bar"></i> Google Analytics Settings</h3>
                    <p>Required for website stats</p>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Tracking Id</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="text" name="analytics_id" class="form-control" placeholder="UA-55555555-1"
                                   maxlength="15" value="<?php
                            if(property_exists($user, 'analytics_id')) {
                                echo $user->analytics_id; };
                            ?>">
                                 <span class="help-block">
                                    This will connect your website to Google Analytics ans starts with UA-
                                </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">View Id <br>(Not Account Id)</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="text" name="account_id" class="form-control" maxlength="10"
                                   value="<?php
                                   if(property_exists($user, 'account_id')) {
                                       echo $user->account_id; };
                                   ?>">
                                 <span class="help-block">
                                    Should consist of all numbers
                                </span>
                        </div>
                    </div>

                    <a href="<?php echo base_url('affiliates/help'); ?>" class="btn btn-info pull-right">Need Help?</a>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="clearfix"></div>
                            <hr>
                        </div>
                    </div>
                    <h3><i class="fa fa-bullhorn"></i> Social Media Settings</h3>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Facebook</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="url" name="facebook" class="form-control"
                                   value="<?php
                                   if(property_exists($page, 'facebook')) {
                                       echo $page->facebook; };
                                   ?>"
                                   maxlength="200" placeholder="Facebook Profile Url">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Twitter</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="url" name="twitter" class="form-control"
                                   value="<?php
                                   if(property_exists($page, 'twitter')) {
                                       echo $page->twitter; };
                                   ?>"
                                   maxlength="200" placeholder="Twitter Profile Url">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Google</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="url" name="google" class="form-control"
                                   value="<?php
                                   if(property_exists($page, 'google')) {
                                       echo $page->google; };
                                   ?>"
                                   maxlength="200" placeholder="Google Profile Url">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Linkedin</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="url" name="linkedin" class="form-control"
                                   value="<?php
                                   if(property_exists($page, 'linkedin')) {
                                       echo $page->linkedin; };
                                   ?>"
                                   maxlength="200" placeholder="LinkedIn Profile Url">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Youtube</label>
                        <div class="clearfix visible-sm visible-xs"></div>
                        <div class="col-md-10">
                            <input type="url" name="youtube" class="form-control"
                                   value="<?php
                                   if(property_exists($page, 'youtube')) {
                                       echo $page->youtube; };
                                   ?>"
                                   maxlength="200" placeholder="Youtube Profile Url">
                        </div>
                    </div>

                    <hr>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Page Settings</button>
                    </div>
                <?php echo form_open(); ?>
            </div>
            <div class="col-md-4">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="icon ion-clock text-success"></i> Auto Settings:</h4>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item"><b>Name:</b>
                                <?php echo $user->first_name. ' '.$user->last_name; ?></li>
                            <li class="list-group-item"><b>Phone:</b> <?php echo '('.
                                    substr($user->phone, 0, 3) .') '.
                                    substr($user->phone, 3, 3) .'-'.
                                    substr($user->phone, 6); ?></li>
                            <li class="list-group-item"><b>Area:</b>  <?php echo $user->city. ' '.$user->state; ?></li>
                        </ul>
                        <p><span class="text-danger">*</span> These details will be used on your website</p>
                        <a href="<?php echo base_url("affiliates/my-account"); ?>" class="btn btn-info">
                            <i class="fa fa-user"></i> Edit Details
                        </a>
						
						<div id="website-media">
							<?php if(!empty($page->image)) { ?>
								<a href="#" class="thumbnail no-margin" data-original-title="" title="">
								  <img src="<?php echo base_url($page->image); ?>" class="img-responsive">
									<div class="deleteImage" data-type="profile"><i class="fa fa-times fa-2x"></i></div>
								</a>
								<br>
							<?php } ?>
							<?php if(!empty($page->background)) { ?>
								<a href="#" class="thumbnail no-margin" data-original-title="" title="">
									<img src="<?php echo base_url($page->background); ?>" class="img-responsive">
									<div class="deleteImage" data-type="background"><i class="fa fa-times fa-2x"></i></div>
								</a>
							<?php } ?>
						</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>