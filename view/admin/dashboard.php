<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>evotech; dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>

  <link href="/view/css/dashboard.css" rel="stylesheet">
</head>

<body>

  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">evotech; admin </a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
      data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="w-100"></div>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="#">Sign out</a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#" onclick="showPage('dashboard')">
                <!-- Add onclick handler -->
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('orders')"> <!-- Add onclick handler -->
                <span data-feather="file"></span>
                Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('products')"> <!-- Add onclick handler -->
                <span data-feather="shopping-cart"></span>
                Products
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('addProduct')"> <!-- Add onclick handler -->
                <span data-feather="plus-square"></span>
                Add Product
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('customers')"> <!-- Add onclick handler -->
                <span data-feather="users"></span>
                Customers
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('import')"> <!-- Add onclick handler -->
                <span data-feather="hard-drive"></span>
                Import Data
              </a>
            </li>
          </ul>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div id="dashboardPage" class="page"> <!-- Add unique ID for each page -->
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Total Users</h5>
                </div>
                <div class="card-body">
                  <h5 class="card-text">100</h5>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Total Products</h5>
                </div>
                <div class="card-body">
                  <h5 class="card-text">100</h5>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Total Orders</h5>
                </div>
                <div class="card-body">
                  <h5 class="card-text">100</h5>
                </div>
              </div>
            </div>
          </div>

          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Quick Links</h1>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title text-center">Add Product</h5>
                </div>
                <div class="card-body">
                  <a href="#" class="btn btn-primary w-100">Go to Add Product Page</a>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title text-center">Import Data</h5>
                </div>
                <div class="card-body">
                  <a href="#" class="btn btn-primary w-100">Go to Import Page</a>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title text-center">View Orders</h5>
                </div>
                <div class="card-body">
                  <a href="#" class="btn btn-primary w-100">Go to Order Page</a>
                </div>
              </div>
            </div>
          </div>



        </div>

        <div id="ordersPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Orders</h1>
          </div>

          <!-- Table displaying orders -->

          <table id="ordersTable" class="table table-striped table-hover" style="width: 100%;">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Products</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table-primary">
                <td>1</td>
                <td>John Doe</td>
                <td>Product 1, Product 2</td>
                <td>2</td>
                <td>100</td>
                <td>Ready</td>
              </tr>
              <tr class="table-success">
                <td>2</td>
                <td>Jane Doe</td>
                <td>Product 3, Product 4</td>
                <td>1</td>
                <td>50</td>
                <td>Delivered</td>
              </tr>
            
            </tbody>
          </table>

        </div>

        <div id="productsPage" class="page" style="display: none;">
          <?php 
          $products = GetAllProducts();
          ?>
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>
          </div>

          <!-- Table displaying products -->

          <table id="productsTable" class="table table-striped table-hover" style="width: 100%;">
            <thead>
              <tr>
                <th>Product Photo</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($products as $item):
              ?>
              <tr>
                <td><img src="/view/images/products/<?php echo $item['ProductID'];?>/<?php echo $item["MainImage"]?>" class="card-img" alt="Product Image" style="width: 100px; height: 100px;"></td>
                <td style="text-align: center;"><?php echo $item['ProductID']; ?></td>
                <td><?php echo $item['Name']; ?></td>
                <td><?php echo $item['Price']; ?></td>
                <td><?php echo $item['Stock']; ?></td>
                <td><?php echo $item['Description']; ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
  
        </div>

        <div id="addProductPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Add Product</h1>
          </div>
          <!-- Add add product content here -->
        </div>

        <div id="customersPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Customers</h1>
          </div>
          <!-- Add customers content here -->
        </div>

        <div id="importPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Import Data</h1>
          </div>
          
        </div>

      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
    integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
    crossorigin="anonymous"></script>
  <script src="/view/js/dashboard.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#ordersTable').DataTable();
      $('#productsTable').DataTable();
    });
  </script>
</body>

</html>