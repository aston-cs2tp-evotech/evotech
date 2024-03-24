<!doctype html>
<html lang="en">

<head>
  <?php
  $products = GetAllProducts();
  if (!$products) {
    $products = [];
  }
  $orders = GetAllOrders();
  if (!$orders) {
    $orders = [];
  }
  $categories = GetAllCategories();
  $customers = GetAllCustomers();
  $admins = GetAllAdmins();
  if (!$admins) {
    $admins = [];
  }

  $tokens = GetAllTokens();
  ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="adminToken" content="<?php echo $_SESSION['adminToken']; ?>">
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
<?php

$categoryChartDatapoints = array();
$categories = GetAllCategories();
foreach($categories as $category) {
  $categoryProducts = GetAllByCategory($category['CategoryName']);
  $categoryProductCount = count($categoryProducts);
  $totalProductCount = count($products);
  $percentage = ($categoryProductCount / $totalProductCount) * 100;
  // Round the percentage to 2 decimal places
  $percentage = round($percentage, 2);
  $categoryChartDatapoints[] = array("label" => $category['CategoryName'] . " (" . $categoryProductCount . ")", "y" => $percentage);
}

$orderStatusChartDatapoints = array();
$orderStatuses = GetAllOrderStatuses();
// Group orders by status
$ordersByStatus = array();
foreach ($orders as $order) {
  $status = $order->getOrderStatusName();
  if (!array_key_exists($status, $ordersByStatus)) {
    $ordersByStatus[$status] = 1;
  } else {
    $ordersByStatus[$status]++;
  }
}
foreach ($orderStatuses as $status) {
  $orderStatusChartDatapoints[] = array("label" => $status['Name'] . " (" . ($ordersByStatus[$status['Name']] ?? 0) . ")", "y" => $ordersByStatus[$status['Name']] ?? 0);
}
?>
<script>
  let categoryChartDataPoints = <?php echo json_encode($categoryChartDatapoints, JSON_NUMERIC_CHECK); ?>;
  let orderStatusChartDataPoints = <?php echo json_encode($orderStatusChartDatapoints, JSON_NUMERIC_CHECK); ?>;
  console.log(orderStatusChartDataPoints);
</script>

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
        <a class="nav-link px-3" href="/adminLogout">Sign out</a>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <?php require 'pages/adminNav.php'; ?>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <?php require 'pages/dashboardPage.php'; ?>
        
        <?php require 'pages/reportPage.php'; ?>

        <?php require 'pages/ordersPage.php'; ?>

        <?php require 'pages/productsPage.php'; ?>

        <?php require 'pages/editProductPage.php'; ?>

        <?php require 'pages/addProductPage.php'; ?>

        <?php require 'pages/customersPage.php'; ?>

        <?php require 'pages/editCustomerPage.php'; ?>

        <?php require 'pages/adminsPage.php'; ?>

        <?php require 'pages/editAdminPage.php'; ?>

        <?php require 'pages/addAdminPage.php'; ?>

        <?php require 'pages/apiTokensPage.php'; ?>

        <?php require 'pages/addAPITokenPage.php'; ?>
        
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
  <script src="/view/js/dashboard.js"></script>

</body>

</html>