<!doctype html>
<html lang="en">

<head>
  <?php
  $products = GetAllProducts();
  $orders = GetAllOrders();
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

          <?php
          // Fetch all order statuses and sort by OrderStatusID
          $allStatuses = GetAllOrderStatuses();
          usort($allStatuses, function ($a, $b) {
            return $a['OrderStatusID'] <=> $b['OrderStatusID'];
          });
          ?>

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
                    $statusClass = 'table-primary'; // Apply primary class for 'basket' status
                    break;
                  case 'ready':
                    $statusClass = 'table-success'; // Apply success class for 'ready' status
                    break;
                  case 'processing':
                    $statusClass = 'table-info'; // Apply info class for 'processing' status
                    break;
                  case 'delivering':
                    $statusClass = 'table-warning'; // Apply warning class for 'delivering' status
                    break;
                  case 'delivered':
                    $statusClass = 'table-dark'; // Apply dark class for 'delivered' status
                    break;
                  case 'cancelled':
                    $statusClass = 'table-danger'; // Apply danger class for 'cancelled' status
                    break;
                  case 'failed':
                    $statusClass = 'table-secondary'; // Apply secondary class for 'failed' status
                    break;
                }
                ?>
                <tr class="<?php echo $statusClass; ?>">
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
                    <?php echo $totalQuantity; ?> x Product 1<br>
                  </td>
                  <td>
                    <?php echo $totalQuantity; ?>
                  </td>
                  <td>£
                    <?php echo $order->getTotalAmount(); ?>
                  </td>
                  <td>
                    <select class="form-select" name="status">
                      <?php foreach ($allStatuses as $status): ?>
                        <option value="<?php echo $status['OrderStatusID']; ?>" <?php echo ($order->getOrderStatusID() == $status['OrderStatusID']) ? 'selected' : ''; ?>>
                          <?php echo $status['Name']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
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
                  <td><img
                      src="/view/images/products/<?php echo $item->getProductID(); ?>/<?php echo $item->getMainImage(); ?>"
                      class="card-img" alt="Product Image" style="width: 100px; height: 100px;"></td>
                  <td style="text-align: center;">
                    <?php echo $item->getProductID(); ?>
                  </td>
                  <td>
                    <?php echo $item->getName(); ?>
                  </td>
                  <td>£
                    <?php echo $item->getPrice(); ?>
                  </td>
                  <td>
                    <?php echo $item->getStock(); ?>
                  </td>
                  <td>
                    <?php echo $item->getDescription(); ?>
                  </td>
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

          <div id="addProductForm">
            <form action="/addProduct" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
              </div>
              <div class="mb-3">
                <label for="productPrice" class="form-label">Price</label>
                <input type="number" class="form-control" id="productPrice" name="productPrice" required>
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
                <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required>
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
  <script src="/view/js/dashboard.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#ordersTable').DataTable({
        // Enable select extension
        select: true,
        // Define columnDefs to customize the status column
        columnDefs: [
          {
            targets: 5, // Index of the status column (0-based index)
            searchable: true, // Allow searching/filtering
            orderable: true, // Allow ordering
          }
        ]
      });
      $('#productsTable').DataTable();
    });
  </script>
</body>

</html>