<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "Quanfey";
$dbname = "db_evoting";

$con = mysql_connect($dbhost, $dbuser, $dbpass) or die("sorry not connected!" . mysql_error());
mysql_select_db($dbname) or die("sorry no db selected" . mysql_error());

function admin($name, $pass) {
    if (mysql_num_rows(mysql_query("SELECT * FROM admin WHERE name='$name' && password='$pass'")) > 0) {
        return true;
    } else {
        return false;
    }
}

 function getName ($name) {
 	$name = mysql_fetch_array(mysql_query("SELECT name FROM admin"));
	return $name['name'];
 }
 
 
function add_candidate($candidate, $photo, $candidate_party, $status) {
    $candidate = clean($candidate);
    $photo = clean($photo);
    $candidate_party = clean($candidate_party);
    $status = clean($status);
    if (!empty($_POST['name'])) {
        if (empty($_POST['status'])) {
            $status = "No";
        } else {
            $status = "Yes";
        }
    }
    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
        $sql = "INSERT INTO evoting(`id`,`name`,`photos`,`partyName`,`votes`,`isCandidate`)
          VALUES ('','$candidate','$photo','$candidate_party','','$status')";
    if (mysql_query($sql) > 0) {
        success("Added successfull.");
    } else {
        error("Added Failed.");
    }
}

function view_candidate() {
    $sql = "SELECT * FROM evoting";
    $result = mysql_query($sql);

    print '<table class="table table-bordered">';
    print '<th><center>Candidate Name</center></th>
            <th><center>Party Name</center></th>
            <th><center>Is Candidate</center></th>
            <th><center>Picture</center></th>
            <th><center>Action</center></th>';

    while ($row = mysql_fetch_assoc($result)) {
        ?> 
        <tr>
            <td><?php echo "<center>" . $row['name'] . "</center>" ?></td>
            <td><?php echo "<center>" . $row['partyName'] . "</center>" ?></td>
            <td><?php
                if (getStatus($row['id']) == "Yes") {
                    ?>
                    <a class="pull-left left10 btn btn-success btn-xs" role="button" onclick="return confirm('Are you sure?');" href="admin.php?status=<?php print $row['id']; ?>"> <i class="fa fa-check"></i>Yes </a>
                <?php } else { ?>
                    <a class = "pull-left left10 btn btn-warning btn-xs" role = "button" onclick = "return confirm('Are you sure?');" href = "admin.php?status=<?php print $row['id']; ?>"> <i class = "fa fa-times"></i>No </a>
                <?php } ?></td> 
            <td><?php echo "<center><img src=img/" . $row['photos'] . " class='img-rounded' alt='Cinque Terre' width='304' height='236'></center></td>" ?>    
            <td><center>
            <a class="btn btn-primary btn-xs" role="button" href="admin.php?id=<?php print $id; ?>&edit=<?php print $row['id']; ?>"><i class="fa  fa-trash-o"></i> Edit</a>    
            <a class="btn btn-danger btn-xs" role="button" href="admin.php?id=<?php print $id; ?>&delete=<?php print $row['id']; ?>"  onclick="return confirm('Are you sure?');"><i class="fa  fa-trash-o"></i> Delete</a>
        </center></td>
        </tr>
        <?php
    }
    print '</table>';
}

function getSingleCandidate($id) {
    $data = mysql_fetch_array(mysql_query("SELECT * FROM evoting WHERE id= '$id' "));
    return $data;
}

function update_candidate($id, $name, $partyName) {
    $name = clean($name);
    //$photo = clean($photo);
    $partyName = clean($partyName);
    $id = clean($id);
    //$target_dir = "img/";
    //$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    // move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    if (mysql_query("UPDATE evoting SET name = '$name', partyName = '$partyName' WHERE id= '$id' ")) {
        success("Successfully updated.");
    } else {
        error("Database problem!");
    }
}

function delete_candidate($id) {
    $id = clean($id);
    if (mysql_query("DELETE FROM evoting WHERE id='$id'")) {
        success("Deleted Successfully .");
    } else {
        error("Database problem!");
    }
}

function getStatus($id) {
    $data = mysql_fetch_array(mysql_query("SELECT isCandidate FROM evoting WHERE id='$id'"));
    return $data['isCandidate'];
}

function changeStatus($id) {

    if (getStatus($id) == "Yes") {
        $status = "No";
    } else {
        $status = "Yes";
    }

    if (mysql_query("UPDATE evoting SET isCandidate = '$status' WHERE id = '$id'")) {
        success("Status Updated.");
    } else {
        error("Status Not updated.");
    }
}

 function getCandidateName ($name,$id) {
 	$name = mysql_fetch_array(mysql_query("SELECT name FROM evoting WHERE id = $id"));
	return $name['name'];
 }
function vote($id, $vote) {
    if (mysql_query("UPDATE evoting SET votes = votes+1 WHERE id = $id")) {
        votemsg("Your Vote Has Been Cast to <h1 style = 'color:red'>" .  getCandidateName(@$name,@$id) . "</h1>" );
    } else {
        error("Database problem!");
    }
}

function error($msg) {
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <?php print $msg ?>
    </div>
    <?php
}

function success($msg) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <?php print $msg ?>
    </div>
    <?php
}

function votemsg($msg) {
    ?>
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><center><?php print $msg ?></center></h3>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function totalVote() {
    $n = mysql_query("SELECT SUM(votes) FROM evoting");
    while ($rows = mysql_fetch_array($n))
        return $rows['SUM(votes)'];
}

function clean($value) {
    return mysql_real_escape_string($value);
}

?>
