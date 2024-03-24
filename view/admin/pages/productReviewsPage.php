<div id="productReviewsPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Product Reviews</h1>

        <a id="resetTableFiltersButton" href="#" class="btn btn-secondary" onclick="resetProductReviewsTable()"
            style="display: none;">Show all Product Reviews</a>
    </div>

    <div id="productReviewsUpdate" class="alert" style="display: none;"></div>

    <table id="productReviewsTable" class="table table-striped table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Customer ID</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Review Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productReviews as $productReview): ?>
                <tr>
                    <td><?php echo $productReview->getProductID(); ?></td>
                    <td><?php echo GetProductByID($productReview->getProductID())->getName(); ?></td>
                    <td><?php echo $productReview->getCustomerID(); ?></td>
                    <td><?php echo $productReview->getRating(); ?></td>
                    <td><?php echo $productReview->getReview(); ?></td>
                    <td><?php echo $productReview->getCreatedAt(); ?></td>
                    <td>
                    <button class="btn btn-primary" onclick="window.open('mailto:<?php echo urlencode(GetCustomerByID($productReview->getCustomerID())->getEmail()); ?>?subject=<?php echo urlencode('Regarding your review on ' . GetProductByID($productReview->getProductID())->getName()); ?>&body=<?php echo urlencode('Your review on ' . GetProductByID($productReview->getProductID())->getName() . ': %0D%0A%0D%0A' . $productReview->getReview() . '%0D%0A%0D%0AReply: '); ?>')">Reply</button>
                        <button class="btn btn-danger" onclick="deleteProductReview(<?php echo $productReview->getProductID(); ?>, <?php echo $productReview->getCustomerID(); ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>