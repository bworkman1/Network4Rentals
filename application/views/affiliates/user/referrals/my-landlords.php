<div class="widget">
    <div class="widget-header">
        <div class="title">
            My Landlords
        </div>
        <span class="mini-title">
            <?php if(!empty($totalRows)) {echo '&nbsp; &nbsp; '.$totalRows.' Total';} ?>
        </span>
        <div class="custom-search hidden-sm hidden-xs">
            <input type="text" class="search-query" placeholder="Search By Name ...">
            <i class="fa fa-search"></i>
        </div>
    </div>
    <div class="widget-body">
        <?php
        $success = $this->session->flashdata('success');
        $error = $this->session->flashdata('error');

        $feedback = '';

        if (!empty($success)) {
            $feedback = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                <b><i class="fa fa-check-circle"></i> Success:</b> '.$success.'
            </div>';
        }

        if (!empty($error)) {
            $feedback = '<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                    <b><i class="fa fa-exclamation-triangle"></i> Error:</b> '.$error.'
                </div>';
        }
        echo $feedback;
        ?>
        <?php if(!empty($affiliates)) {  ?>
            <table class="table table-hover no-margin">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Signed Up</th>
                    <th>Email</th>
                    <th>Zip</th>
                    <th>Phone</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($affiliates as $row) {
                    echo '<tr>';
                        echo '<td>'.$row->id.'</td>';
                        echo '<td>'.$row->name.'</td>';
                        echo '<td>'.date('m-d-Y', strtotime($row->sign_up)).'</td>';
                        echo '<td>'.$row->email.'</td>';
                        echo '<td>'.$row->zip.'</td>';
                        echo '<td>';
                    if (!empty($row->phone)) {
                        echo substr($row->phone, 0, 3) . ') ' .
                            substr($row->phone, 3, 3) . '-' .
                            substr($row->phone, 6);
                    } else {
                        echo 'NA';
                    }
                    echo '</td>';
                }
                ?>
                </tbody>
            </table>
            <hr>
            <div class="pull-right"><?php echo $links; ?></div>
            <div class="clearfix"></div>

        <?php } else { ?>
            <div class="alert alert-info">
                <h3>You have not signed up any Landlords yet</h3>
                <p>Once a renter creates an account with your affiliate id attached to it,
                    you will see them show up here.</p>

            </div>
        <?php } ?>
    </div>
</div>