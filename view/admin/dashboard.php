<!doctype html>
<html lang="en">

<head>
  <?php
  $products = GetAllProducts();
  $orders = GetAllOrders();
  if (!$orders) {
    $orders = [];
  }
  $categories = GetAllCategories();
  ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>evotech; dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('orders')"> 
                <span data-feather="file"></span>
                Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('products')"> 
                <span data-feather="shopping-cart"></span>
                Products
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('addProduct')"> 
                <span data-feather="plus-square"></span>
                Add Product
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('customers')"> 
                <span data-feather="users"></span>
                Customers
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('admins')"> 
                <span data-feather="hard-drive"></span>
                Admins
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onclick="showPage('import')"> 
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
                  <h5 class="card-text"><?php echo GetCustomerCount();?></h5>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Total Products</h5>
                </div>
                <div class="card-body">
                  <h5 class="card-text"><?php echo GetProductCount();?></h5>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Total Orders</h5>
                </div>
                <div class="card-body">
                  <h5 class="card-text"><?php echo count($orders);?></h5>
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
                  <h5 class="card-title text-center">View Database</h5>
                </div>
                <div class="card-body">
                  <a href="/phpmyadmin" class="btn btn-primary w-100">Go to phpMyAdmin</a>
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

          <!-- Low stock products -->
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h3">Low Stock Products</h1>
          </div>
          <div class="row">
            <?php
            // Get low stock products by filtering products with stock less than 15
            $lowStockProducts = array_filter($products, function ($product) {
              return $product->getStock() < 15;
            });
            ?>
            <table id="lowStockProductsTable" class="table table-striped table-hover" style="width: 100%;">
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Product Name</th>
                  <th>Stock</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lowStockProducts as $product): 
                    // Determine the stock level class based on the stock level
                    $stockLevelClass = '';
                    $stock = $product->getStock();
                    if ($stock >= 10 && $stock <= 15) {
                        $stockLevelClass = 'table-warning'; // Apply warning class for stock level between 10 and 15
                    } elseif ($stock >= 1 && $stock <= 10) {
                        $stockLevelClass = 'table-danger'; // Apply danger class for stock level between 1 and 10
                    } else {
                        $stockLevelClass = 'table-dark'; // Apply dark class for stock level less than 1
                    }
                ?>
                  <tr class="<?php echo $stockLevelClass; ?>">
                    <td>
                      <?php echo $product->getProductID(); ?>
                    </td>
                    <td>
                      <?php echo $product->getName(); ?>
                    </td>
                    <td>
                      <b><?php echo $product->getStock(); ?></b>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            
          </div>

        </div>

        <div id="ordersPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Orders</h1>
          </div>

          <?php
          // Fetch all order statuses and sort by OrderStatusID
          $allStatuses = GetAllOrderStatuses();
          usort($allStatuses, function ($a, $b) {
            return $a['OrderStatusID'] <=> $b['OrderStatusID'];
          });
          ?>

          <div id="orderUpdate" class="alert" style="display: none;"></div>


          <!-- Table displaying orders -->
          <table id="ordersTable" class="table table-striped table-hover" style="width: 100%;">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Products</th>
                <th>Total Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
                <?php
                $totalQuantity = 0;
                $statusClass = ''; // Initialize an empty string for the status class
                ?>
                <?php
                // Determine the status class based on the order status
                switch ($order->getOrderStatusName()) {
                  case 'basket':
                    $statusClass = ''; 
                    $modifiable = false; 
                    break;
                  case 'ready':
                    $statusClass = 'table-primary'; 
                    $modifiable = true;
                    break;
                  case 'processing':
                    $statusClass = 'table-info'; 
                    $modifiable = true;
                    break;
                  case 'delivering':
                    $statusClass = 'table-warning'; 
                    $modifiable = true;
                    break;
                  case 'delivered':
                    $statusClass = 'table-success'; 
                    $modifiable = true;
                    break;
                  case 'cancelled':
                    $statusClass = 'table-danger'; 
                    $modifiable = true;
                    break;
                  case 'failed':
                    $statusClass = 'table-danger'; 
                    $modifiable = true;
                    break;
                }
                ?>
                <tr class="<?php echo $statusClass; ?>" id="ordersTableRow<?php echo $order->getOrderID(); ?>">
                  <td>
                    <?php echo $order->getOrderID(); ?>
                  </td>
                  <td>
                    <?php echo $order->getCustomerID(); ?>
                  </td>
                  <td>
                    <?php foreach ($order->getOrderLines() as $orderLine): ?>
                      <?php echo $orderLine->getQuantity() . " x " . $orderLine->getProductName() . "<br>"; ?>
                      <?php $totalQuantity += $orderLine->getQuantity(); ?>
                    <?php endforeach; ?>
                  </td>
                  <td>
                    <?php echo $totalQuantity; ?>
                  </td>
                  <td>£
                    <?php echo $order->getTotalAmount(); ?>
                  </td>
                  <td>
                    <?php if ($modifiable): ?>
                      <select class="form-select" name="status">
                        <?php foreach ($allStatuses as $status): ?>
                          <?php if ($status['Name'] !== 'basket' || $order->getOrderStatusName() === 'basket'): ?>
                            <option value="<?php echo $status['OrderStatusID']; ?>" <?php echo ($order->getOrderStatusID() == $status['OrderStatusID']) ? 'selected' : ''; ?>>
                              <?php echo $status['Name']; ?>
                            </option>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </select>
                    <?php else: ?>
                      <span><?php echo $order->getOrderStatusName(); ?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>


          </table>
        </div>



        <div id="productsPage" class="page" style="display: none;">

          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>
          </div>
          
          <?php if (isset($_GET['editProductError'])): ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $_GET['editProductError']; ?>
            </div>
            <?php elseif (isset($_GET['editProductSuccess'])): ?>
              <div class="alert alert-success" role="alert">
                <?php echo $_GET['editProductSuccess']; ?>
              </div>
          <?php endif; ?>
          
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
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $item): ?>
                  <?php

                      $stockLevelClass = '';
                      $stock = $item->getStock();
                      if ($stock >= 15) {
                          $stockLevelClass = ''; 
                      } elseif ($stock >= 10 && $stock <= 15) {
                          $stockLevelClass = 'table-warning'; 
                      } elseif ($stock >= 1 && $stock <= 10) {
                          $stockLevelClass = 'table-danger'; 
                      } else {
                          $stockLevelClass = 'table-dark'; 
                      }
                  ?>
                  <tr class="<?php echo $stockLevelClass; ?>">
                      <td style="max-width: 120px;"><img src="/view/images/products/<?php echo $item->getProductID(); ?>/<?php echo $item->getMainImage(); ?>" class="card-img" alt="Product Image" style="width: 100px; height: 100px;"></td>
                      <td style="text-align: center;"><?php echo $item->getProductID(); ?></td>
                      <td><?php echo $item->getName(); ?></td>
                      <td>£<?php echo $item->getPrice(); ?></td>
                      <td><?php echo $item->getStock(); ?></td>
                      <td><?php echo $item->getDescription(); ?></td>
                      <td>
                          <a class="btn btn-primary" onclick="showPage('editProduct', <?php echo $item->getProductID(); ?>)">Edit</a>
                          <a href="/product?productID=<?php echo $item->getProductID(); ?>" class="btn btn-primary">View Page</a>
                      </td>
                  </tr>
              <?php endforeach; ?>
          </tbody>

          </table>

        </div>

                <div id="editProductPage" class="page" style="display: none;">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Product</h1>
            </div>

            <div id="editProductForm">
                <form action="/api/editProduct" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="editproductID" name="productID">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="editproductName" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Price in £</label>
                        <input type="number" class="form-control" id="editproductPrice" name="productPrice" min="0.01" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="productStock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="editproductStock" name="productStock" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editproductDescription" name="productDescription" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Category</label>
                        <select class="form-select" id="editproductCategory" name="productCategory" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['CategoryID']; ?>">
                                    <?php echo $category['CategoryName']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" onchange="previewImage(this,true)">
                    </div>

                    <div class="mb-3">
                        <img id="editProductimagePreview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px;">
                    </div>
                    <div>
                      <button type="submit" class="btn btn-primary">Update Product</button>
                      <button type="button" class="btn btn-danger" onclick="deleteProduct()">Delete Product</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="addProductPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Add Product</h1>
          </div>

          <div id="addProductForm">
            <form action="/api/addProduct" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
              </div>
              <div class="mb-3">
                <label for="productPrice" class="form-label">Price in £</label>
                <input type="number" class="form-control" id="productPrice" name="productPrice" min = "0.01" step = "0.01" required>
              </div>
              <div class="mb-3">
                <label for="productStock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="productStock" name="productStock" required>
              </div>
              <div class="mb-3">
                <label for="productDescription" class="form-label">Description</label>
                <textarea class="form-control" id="productDescription" name="productDescription" required></textarea>
              </div>
              <div class="mb-3">
                <label for="productCategory" class="form-label">Category</label>
                <select class="form-select" id="productCategory" name="productCategory" required>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['CategoryID']; ?>">
                      <?php echo $category['CategoryName']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="productImage" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required onchange="previewImage(this)">
              </div>
                
               <div class="mb-3">
                  <img id="addProductimagePreview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px;">
                </div>

              <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
          </div>

        </div>

        <div id="customersPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Customers</h1>
          </div>

        </div>

        <div id="adminsPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Admins</h1>
          </div>

        </div>

        <div id="importPage" class="page" style="display: none;">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Import Data</h1>
          </div>

          <form action="/importData" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="dummyData" class="form-label">Import Dummy Data</label>
              <button type="submit" class="btn btn-primary" name="dummyData">Import</button>
            </div>
            <div class="mb-3">
              <label for="fileData" class="form-label">Import from File</label>
              <input type="file" class="form-control" id="fileData" name="fileData" accept=".csv, .xlsx">
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
          </form>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
  <script src="/view/js/dashboard.js"></script>
</body>

</html>