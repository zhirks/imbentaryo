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
      <!-- Modal -->
      <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">  
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Login</h5>
            </div>
            <form id="loginForm">
              <div class="modal-body">
                  <div class="alert alert-warning d-none" id="errorMessage"></div>
                  <div class="mb-3">
                    <label >Username:</label>
                    <input type="text" name="username" class="form-control"/>
                  </div>
                  <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control"/>
                  </div>
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
      </div>

    <div class="container">
      <nav class="navbar navbar-expand-lg">
        <div class="container py-3">
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
                <a class="nav-link fs-6" href="products.php">Products</a>
              </li>
              <li class="nav-item">
                <a class="nav-link btn btn-primary text-white px-3 py-2 ms-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                <!--button type="button" class="btn btn-primary px-5 py-3 d-inline-block" data-bs-toggle="modal" data-bs-target="#loginModal">Upload Now</button-->
              </li>
            </ul>
          </div>
        </div>
      </nav>
    
      <!-- UPLOADS -->

      <div class="container-fluid">
        <div class="row align-items-center justify-content-center">
          <div class="col-12 col-lg-6">
              <h1 class="display-4 fw-bold">Seamless inventory management</h1>
              <p class="lead">Using your latest excel file, update your inventory stocks by uploading in few seconds!</p>
              <button type="button" class="btn btn-primary px-5 py-3 d-inline-block" data-bs-toggle="modal" data-bs-target="#loginModal">Upload Now</button>
          </div>
          <div class="col-12 col-lg-6 py-2">
            <img src="assets/inv-large.jpg" alt="inventory management image" class="img-fluid ms-auto">
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
                $('.modal-backdrop').remove();
                window.location.href = 'dashboard.php';
              }

              if(res.status == 401){
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
              }

            }
          });
        });
    </script>
  </body>
</html>