<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="admindashboard.css">
    <title>EvoTech</title>
    <ion-icon name="desktop-outline"></ion-icon>


</head>
<body>
    <div class="d-flex "> 
        <div class="d-flex flex-column flex-shrink-0 p-3 text-light bg-dark" id="sidebar-wrapper">
            <!-- Sidebar content -->
            <div class="mb-3">
                <a href="#" class="d-flex align-items-center text-light">
                    <span class="fs-3">Evotech</span>
                </a>
            </div>
            <hr>
            <!-- Navigation Links -->
            <div>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link active text-light">
                            <i class="bi bi-house">Dashboard</i></a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-light">
                            <i class="bi bi-archive">Inventory Management</i></a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-light">
                            <i class="bi bi-person"> Customer transactions </i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">
                          <i class="bi bi-plus-circle"> Add product</i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">
                          <i class="bi bi-box-arrow-left"> Logout</i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
      <div class="container-fluid p-4 bg-secondary">
        <section class="p-1">
          <div class="container">
           <div class="row">
              <div class="row text-center">

                  <div class="col-md">
                      <div class="card bg-light text-dark" >
                          <div class="card-body">
                            <h5 class="card-title">Number of products</h5>
                            <p class="card-text">Insert text.</p>
                          </div>
                          
                      </div>
                  </div>
                  <div class="col-md">
                      <div class="card bg-light text-dark" >
                          <div class="card-body">
                            <h5 class="card-title">Number of sales</h5>
                            <p class="card-text">Insert text</p>
                          </div>
                      </div>   
  
                  </div>
                  <div class="col-md">
                     <div class="card bg-light text-dark" >
                        <div class="card-body">
                          <h5 class="card-title">Insert text</h5>
                          <p class="card-text">Insert text.</p>
                        </div>  
                      </div>
  
                  </div>
              </div>
           </div>
          </div>
      </section>
      <div class="table">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope ="col">#></th>
                    <th scope ="col">Name></th>
                    <th scope ="col">#></th>
                    <th scope ="col">#></th>

                </tr>
            </thead>
            <tbody class = "table-group-divider">
                <tr>
                    <th scope="row">1</th>
                    <td>Example</td>
                    <td></td>
                    <td></td>
                  </tr>
                  
                
            </tbody>

        </table>
      </div>
    </div>
    
    

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>
</html>