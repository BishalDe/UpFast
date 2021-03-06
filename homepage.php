<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
  header('Location: index.php');
  exit();
}
?>

<?php
require 'partials/_database.php';
$tablename=$_SESSION['username'];
/*$sql = 'CREATE TABLE IF NOT EXISTS data (sno int(200) auto_increment primary key,title varchar(100) NOT NULL, description varchar(255) NOT NULL,postdate DATETIME)';*/
$sql = "CREATE TABLE IF NOT EXISTS $tablename (sno int(200) auto_increment primary key,title varchar(100) NOT NULL, filename varchar(255) NOT NULL,postdate DATETIME)";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_SESSION['username'];
  $title = $_POST['title'];
  $file_name=$_FILES['filess']['name'];
  $file_size=$_FILES['filess']['size']; 
  $file_tmp=$_FILES['filess']['tmp_name']; 
  $file_type=$_FILES['filess']['type']; 
  move_uploaded_file($file_tmp,"uploadedfiles/". $file_name);

  if(!($title == '')){
  $sql = "INSERT INTO $name (title,filename,postdate) VALUES ('$title','$file_name',CURRENT_TIMESTAMP());";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>SUCCESS!</strong> Added Successfully..!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  } else {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>FAILED!</strong> Not Saved...!!!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  }}
  else{
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>FAILED!</strong> Please Enter title and Description!!!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UpFast</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>
<?php
require 'partials/_nav.php';
?>
  <div class="container my-5">
    <h1>Welcome <strong><?php echo $_SESSION['username']; ?></strong>..!</h1>  <h3>Add Files Here..!</h3>
    <form action='homepage.php' method='post' enctype="multipart/form-data"> 
      <div class="form-group">
        <label for="exampleInputEmail1">Title</label>
        <input type="text" class="form-control" id="title" aria-describedby="emailHelp" placeholder="Enter Title" name="title">
        <small id="emailHelp" class="form-text text-muted">Title For Your Files-..!</small>
      </div>
      <div class="mb-3">
  <label for="formFileMultiple" class="form-label"><b>Only one file at a time..</b></label>
  <input class="form-control" type="file" id="formFileMultiple" name="filess">
</div>
      <button type="submit" class="btn btn-primary my-4">Add File</button>
      <button type="reset" class="btn btn-dark my-4">Reset</button>
    </form>
  </div>
  <div class="container">
    <table class="table table-striped table-hover" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Title</th>
          <th scope="col">Post Date</th>
          <th scope="col">File Name</th>
          <th scope="col">Files</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $name = $_SESSION['username'];
        $sql = "SELECT * FROM $name";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        echo '<h5> Total number Of Records : ' . $num . '</h5><br>';
        if ($num > 0) {
          $sno = 1;
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                        <th scope="row">' . $sno . '</th>
                        <td>' . $row['title'] . '</td>
                        <td>' . $row['postdate'] . '</td>
                        <td>' . $row['filename'] . '</td>
                        <td>
                        <a href="uploadedfiles/'.$row['filename'].'" download>
                          Download
                        </a>
                        </td>
                        <td>
                        <a href="uploadedfiles/'.$row['filename'].'"  target="_blank" >
                          Print
                        </a>
                        </td>
                  </tr>';
            $sno += 1;
          }
        }
        
        ?>
      </tbody>
    </table>
  </div>

</body>
<script>
    edits=document.getElementsByClassName('edit');
    Array.from(edits).forEach((element)=>{
      element.addEventListener('click',(e)=>{
         console.log("edit",e);
      })
    })
  </script>
</html>