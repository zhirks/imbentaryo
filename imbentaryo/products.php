<?php
  session_start();

  // check for logged in session
  /* this is working as well
  if(!isset($_SESSION['loggedIn']))
  {
    header("Location: index.php");
    exit;
  }
  */

  if (!$_SESSION['loggedIn']) {
    header("Location: index.php");
    exit;
  }

  if(isset($_SESSION["user_id"])){
    $mysqli = require __DIR__ . "/dbconnect.php";

    $sql = "SELECT * FROM login
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
  }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"/>
  </head>
  <body>

    <div class="container">
      <nav class="navbar navbar-expand-lg">
        <div class="container py-3 px-0">
          <a class="navbar-brand fw-bold fs-2" href="#">imbentaryo</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active fs-6" aria-current="page" href="dashboard.php">Dashboard</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fs-6" href="stores.php">Stores</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fs-6" href="#">Products</a>
              </li>
              <?php
                echo $_SESSION['loggedIn'] ? '<li class="nav-item"><a class="nav-link btn btn-primary text-white px-3 py-2 ms-2 logout" href="logout.php">Logout</a></li>' : '';
              ?>
            </ul>
          </div>
        </div>
      </nav>
    
      <!-- hero sections -->

      <div class="container-fluid">
        <div class="row align-items-center justify-content-center">
          <div class="row">
            <div class="col-lg-12 mb-4 order-0 px-0">
              <div class="card">
                <div class="d-flex align-items-end row">
                  <div class="col-sm">
                    <div class="card-body">
                      <h5 class="card-title text-primary">List of Stores</h5>
                      <br/>
                      <table class="table table-striped table-hover">
                        <thead>
                          <tr>
                              <th>Product Name</th>
                              <th>Product Variant</th>
                              <th>Product Specifications</th>
                              <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM product";
                            $result = $mysqli->query($sql); 

                            if(!$result){
                              die("Invalid query: " .$mysqli->error);
                            }

                            //read data for each row
                            while($row = $result->fetch_assoc()){
                              echo "<tr>  
                              <td>".$row['prod_name']."</td>
                              <td>".$row['prod_variant']."</td>
                              <td>".$row['prod_specs']."</td>
                              <td>
                                <div class='btn-group btn-group-sm' id='update-delete-btn'>
                                  <a href='update.php?id=$row[prod_id]' class='btn btn-info'>Update</a>
                                  <a href='delete.php?id=$row[prod_id]'' class='btn btn-danger'>Delete</a>
                                </div>
                              </td>
                            </tr>";
                            }
                            ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"crossorigin="anonymous"></script>

    <script>
        $(document).on('submit', '#loginForm', function(e){
          e.preventDefault();

          var loginData = new FormData(this);
          loginData.append("login_form", true);

          $.ajax({
            type: "POST",
            url: "login.php",
            data: loginData,
            processData: false,
            contentType: false,
            success: function (response){
              var res = jQuery.parseJSON(response);

              if(res.status == 422){
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
              }
              else if(res.status == 200){
                $('#errorMessage').addClass('d-none');
                $('#loginModal').modal('hide');
                $('#loginForm')[0].reset();
              }
            }
          });
        });
    </script>
  </body>
</html>