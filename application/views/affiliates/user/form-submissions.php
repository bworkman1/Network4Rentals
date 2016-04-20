<?php
    if (!empty($table)) { ?>
        <div class="row">
          <div class="col-md-8">
              <div class="widget">
                <div class="widget-header">
                    <div class="title">
                        <i class="fa fa-user"></i> User Submitted Form
                    </div>
                </div>
                <div class="widget-body">
                    <?php echo $table; ?>
                </div>
            </div>
          </div>
        </div>
    <?php } else {
        echo '<div class="alert alert-info"><h3>No Form Submissions Yet!</h3>
        <p>Once a visitor submits the form on your website it will show up here.</p></div>"';
    }
?>