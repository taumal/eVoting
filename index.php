<?php
session_start();
if (isset($_SESSION['name'])) {
    session_destroy();
}
include_once('assets/helper/adminAuh.php');
if (isset($_POST['name']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $pass = $_POST['password'];
    if (admin($name, $pass)) {
        $_SESSION['name'] = $name;
        header("location:admin.php");
    }else{
            $error = true;
        } 
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Online Voting System</title>

        <!-- Bootstrap Core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
        <!-- css design for this page -->
        <link href="assets/css/home.css" rel="stylesheet">

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
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Online Voting System</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <div class="navbar-form navbar-right">
                        <a id="login-trigger" class="navbar-right btn btn-primary btn-nav">Admin Login</a>
                        <div id="login-content">

                            <?php
                            if (isset($error)) {
                                error("User name and password did not match.");
                            }
                            ?>

                            <form  class="form" action="index.php" method="POST">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="">Name</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" name="name" placeholder="Name" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="password1">Password</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="password" name="password" placeholder="Password" required="">
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-sm-3 col-sm-offset-9">
                                        <input class="btn btn-primary btn-sm" type="submit" id="submit" value="Log in">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>            
            <!-- </div> -->
            <!-- </div> -->
        </nav>
        <?php
        if (isset($_GET['vote'])) {
            @$id = $_GET['id'];
            @$vote = $_GET['votes'];
            vote($id, $vote);
        }
        ?>
        <div class="container">
            <br>
            <div class="well well-sm"><h1 style="color: darkslateblue"><center>Which Politician Do You Want To Elected As A Great Leader?</center></h1></div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    $sql = mysql_query("SELECT * FROM evoting where isCandidate = 'Yes'");
                    if (mysql_num_rows($sql)) {
                        while ($row = mysql_fetch_assoc($sql)) {
                            print     "<div class = 'col-md-4'>"
                                    . "<div class=\"progress\">
                                            <div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"40\"
                                                aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:" . round($row['votes'] * 100 / totalVote()) . "%\">" . round($row['votes'] * 100 / totalVote()) . "%" .
                                            "</div>
                                        </div>"
                                    . "<a href = '' class = 'thumbnail'>"
                                    . "<img src=img/" . $row['photos'] . " class='img-rounded' "
                                    . "alt = '' style = 'width:280px; height:280px'></a>"
                                    . "<form action = 'index.php' methode = 'post'>"
                                    . "<center><input type='hidden' name='id' value='" . $row['id'] . "'>"
                                    . "<input class='btn btn-primary btn-lg' type='submit' value=' " . $row['name'] . "' name='vote'></center><br></form>"
                                    . "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="panel-footer"><center>Developed by Eban</center></div>
        </div>
        
        <!-- jQuery -->
        <script src="assets/js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- jquery for login box -->
        <script type="text/javascript">
            $(document).ready(function () {
                $('#login-trigger').click(function () {
                    $(this).next('#login-content').slideToggle();
                    if ($(this).s('active'))
                        $(this).find('span').html('&#x25BC;')
                    else
                        $(this).find('span').html('&#x25B2;')
                })
            });

            //dismiss any alert message box
            $(".alert").delay(2000).fadeTo(1000, 500).slideUp(150, function () {
                $(this).alert('close');
            });
        </script>
        <script type="text/javascript">
            $(function () {
                $('#myModal').modal('show');
            });
        </script>
        <?php
    /** show login box again after submit **/ 
    if(isset($_POST['name']) && isset($_POST['password'])) { 
    ?> 
        <script type="text/javascript">
            $(function() {                       
                $('#login-content').slideToggle();
            });
        </script>
    <?php 
    } ?>
    </body>
</html>
