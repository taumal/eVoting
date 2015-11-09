<?php
session_start();
if (isset($_SESSION['name'])) {
    require_once '.\assets\helper\adminAuh.php';
    ?> 
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="">
            <meta name="author" content="">

            <title>Admin Panel</title>

            <!-- Bootstrap Core CSS -->
            <link href="assets/css/bootstrap.min.css" rel="stylesheet">
            <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
            <!-- Custom CSS -->

            <link href="assets/css/admin.css" rel="stylesheet">
            <link href="assets/css/home.css" rel="stylesheet">

            <!-- Custom Fonts -->
            <link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">


            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->

        </head>

        <body>
            <!-- navbar -->
            <nav class="navbar navbar-default">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="admin.php">Online Voting system</a> 
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <!-- /.dropdown -->
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-user fa-fw"></i><i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout (<?php print getName(@$name); ?>)</a></li>
                            </ul>
                            <!-- /.dropdown-user -->
                        </li>
                    </ul>
                </div>      
            </nav>
            <?php
            if (isset($_POST['save'])) {
                $candidate = $_POST['name'];
                $candidate_party = $_POST['partyName'];
                $photo = $_FILES["fileToUpload"]["name"];
                $status = @$_POST['status'];
                add_candidate($candidate, $photo, $candidate_party, $status);
            }
            if (isset($_GET['status'])) {
                changeStatus($_GET['status']);
            } else {
                if (isset($_GET['delete'])) {
                    delete_candidate($_GET["delete"]);
                }
            }
            ?>
            <?php
            if (isset($_POST['edited'])) {
                update_candidate($_POST['id'], $_POST['name'], $_POST['partyName']);
            }
            ?>

            <div class="container">
                <h1>Manage Candidate</h1>
                <hr>           
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-plus-circle"></i> Add Candidate </div>
                    <div class="panel-body"> 
                        <form class="form-horizontal" role="form" action="admin.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Candidate Name:</label>
                                <div class="col-sm-6">
                                    <input type="text" name="name" class="form-control" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Party Name: </label>
                                <div class="col-sm-6">
                                    <input type="text" name="partyName" class="form-control" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Candidate Photo: </label>
                                <div class="col-sm-6">
                                    <input required name="fileToUpload" class="form-control" type="file">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2"> Is Candidate</label>
                                <div class="col-sm-6">
                                    <input name="status" type="checkbox">
                                </div>
                            </div>
                            <button type="submit" name="save" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
                <h1>Candidate Listing</h1>
                <hr>           
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-cog"></i> Candidate Listing </div>
                    <div class="panel-body"> 
                        <?php view_candidate() ?>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title text-info" id="myModalLabel">Edit Candidate Name</h4>
                        </div>
                        <div class="modal-body">
                            <?php
                            if (isset($_GET['edit'])) {
                                $id = clean($_GET['edit']);
                                $data = getSingleCandidate($id);
                            }
                            ?>
                            <form class="form-horizontal" role="form" action="admin.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div class="col-sm-9">
                                        <input name="id" type="hidden" value="<?php print $data['id']; ?>">
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Candidate Name:</label>
                                    <div class="col-sm-6">
                                        <input value="<?php print $data['name']; ?>"type="text" name="name" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Party Name: </label>
                                    <div class="col-sm-6">
                                        <input value="<?php print $data['partyName']; ?>"type="text" name="partyName" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="edited" class="btn btn-success">Update</button>
                                </div>
                            </form>
                            <!-- </form> -->         
                        </div>
                    </div>
                </div>
                <!-- /#end dialog box -->

                <script src="./assets/js/jquery.min.js"></script>

                <!-- Bootstrap Core JavaScript -->
                <script src="./assets/js/bootstrap.min.js"></script>

                <!-- Metis Menu Plugin JavaScript -->

                <script src="./assets/js/metisMenu.min.js"></script>

                <!-- Custom Theme JavaScript -->
                <script src="./assets/js/sb-admin-2.js"></script>

                <?php
                /** show Edit box * */
                if (isset($_GET['edit'])) {
                    ?> 
                    <script type="text/javascript">
                        $(function () {
                            $('#modal').modal('show');
                        });
                    </script>
                    <?php
                }
                ?>
                <!-- jquery for login box -->
                <script type="text/javascript">
                    //dismiss any alert message box
                    $(".alert").delay(1000).fadeTo(1000, 500).slideUp(200, function () {
                        $(this).alert('close');
                    });
                </script>
                }
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>