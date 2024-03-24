<div id="productsPage" class="page" style="display: none;">

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Products</h1>

        <a href="#" class="btn btn-secondary" onclick="showPage('addProduct')">Add New Product</a>
    </div>

    <?php if (isset ($_GET['editProductError'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_GET['editProductError']; ?>
        </div>
    <?php elseif (isset ($_GET['editProductSuccess'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_GET['editProductSuccess']; ?>
        </div>
    <?php endif; ?>

    <div id="productUpdate" class="alert" style="display: none;"></div>


    <table id="productsTable" class="table table-striped table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>Product Photo</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
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
                <tr class="<?php echo $stockLevelClass; ?>" id="productsTableRow<?php echo $item->getProductID(); ?>">
                    <td id="columnProductImage_<?php echo $item->getProductID(); ?>" style="max-width: 120px;"><img
                            src="/view/images/products/<?php echo $item->getProductID(); ?>/<?php echo $item->getMainImage(); ?>"
                            class="card-img" alt="Product Image" style="width: 100px; height: 100px;"></td>
                    <td id="columnProductID_<?php echo $item->getProductID(); ?>" style="text-align: center;">
                        <?php echo $item->getProductID(); ?>
                    </td>
                    <td id="columnProductName_<?php echo $item->getProductID(); ?>">
                        <?php echo $item->getName(); ?>
                    </td>
                    <td id="columnProductCategory_<?php echo $item->getProductID(); ?>">
                        <?php echo $item->getCategoryName(); ?>
                    </td>
                    <td id="columnProductPrice_<?php echo $item->getProductID(); ?>">£
                        <?php echo $item->getPrice(); ?>
                    </td>
                    <td id="columnProductStock_<?php echo $item->getProductID(); ?>">
                        <?php echo $item->getStock(); ?>
                    </td>
                    <td id="columnProductDescription_<?php echo $item->getProductID(); ?>">
                        <?php echo $item->getDescription(); ?>
                    </td>
                    <td id="columnProductActions_<?php echo $item->getProductID(); ?>" style="text-align: center;">
                        <a class="btn btn-primary m-1"
                            onclick="showPage('editProduct', <?php echo $item->getProductID(); ?>)">Edit</a>
                        <a href="/product?productID=<?php echo $item->getProductID(); ?>" class="btn btn-secondary">View
                            Page</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>