<div id="reportPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Report</h1>

        <!-- Print Button with print icon -->
        <a href="#" class="btn btn-secondary" onclick="printDiv('reportPage')">Print</a>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title" style="text-align: center;">Total Users</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-text" style="text-align: center;">
                        <?php echo GetCustomerCount(); ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title
                  " style="text-align: center;">Total Products</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-text" style="text-align: center;">
                        <?php echo GetProductCount(); ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title
                  " style="text-align: center;">Total Orders</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-text" style="text-align: center;">
                        <?php echo count($orders); ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Low Stock Products</h1>
    </div>
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

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Charts</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div id="categoryChart" width="400" height="400"></div>
        </div>
        <div class="col-md-6">
            <div id="statusChart" width="400" height="400"></div>
        </div>
    </div>


</div>