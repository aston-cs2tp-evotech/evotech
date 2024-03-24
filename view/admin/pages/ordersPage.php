<div id="ordersPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Orders</h1>

        <a id="resetTableFiltersButton" href="#" class="btn btn-secondary" onclick="resetOrdersTable()"
            style="display: none;">Show all Orders</a>
    </div>

    <?php
    // Fetch all order statuses and sort by OrderStatusID
    $allStatuses = GetAllOrderStatuses();
    usort($allStatuses, function ($a, $b) {
        return $a['OrderStatusID'] <=> $b['OrderStatusID'];
    });
    ?>

    <div id="orderUpdate" class="alert" style="display: none;"></div>


    <table id="ordersTable" class="table table-striped table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Products</th>
                <th>Total Quantity</th>
                <th>Total Price</th>
                <th>Checked Out Date</th>
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
                    case 'returned':
                        $statusClass = 'table-dark';
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
                        <?php echo $order->getCheckedOutAt(); ?>
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
                            <span>
                                <?php echo $order->getOrderStatusName(); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>


    </table>
</div>