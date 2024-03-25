<div id="dashboardPage" class="page">
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
                    <h5 class="card-text">
                        <?php echo GetCustomerCount(); ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Total Products</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-text">
                        <?php echo GetProductCount(); ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Total Orders</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-text">
                        <?php echo count($orders); ?>
                    </h5>
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
                    <h5 class="card-title text-center">View Products</h5>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-secondary w-100" onclick="showPage('products')">Go to Products</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">View Database</h5>
                </div>
                <div class="card-body">
                    <a href="/phpmyadmin" class="btn btn-secondary w-100">Go to phpMyAdmin</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">View Orders</h5>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-secondary w-100" onclick="showPage('orders')">Go to Orders</a>
                </div>
            </div>
        </div>
    </div>

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Low Stock Products</h1>
    </div>
    <div class="row">
        <?php
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
                        $stockLevelClass = 'table-warning';
                    } elseif ($stock >= 1 && $stock <= 10) {
                        $stockLevelClass = 'table-danger';
                    } else {
                        $stockLevelClass = 'table-dark';
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
                            <b>
                                <?php echo $product->getStock(); ?>
                            </b>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>

</div>