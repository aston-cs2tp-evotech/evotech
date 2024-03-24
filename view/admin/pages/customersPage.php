<div id="customersPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Customers</h1>
    </div>

    <div id="customerUpdate" class="alert" style="display: none;"></div>

    <?php
    ?>

    <table id="customersTable" class="table table-striped table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Address</th>
                <th>Date Created</th>
                <th>Last Date Modified</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr id="customersTableRow<?php echo $customer->getUID(); ?>">
                    <td id="columnCustomerID_<?php echo $customer->getUID(); ?>">
                        <?php echo $customer->getUID(); ?>
                    </td>
                    <td id="columnCustomerEmail_<?php echo $customer->getUID(); ?>">
                        <?php echo $customer->getEmail(); ?>
                    </td>
                    <td id="columnCustomerUsername_<?php echo $customer->getUID(); ?>">
                        <?php echo $customer->getUsername(); ?>
                    </td>
                    <td id="columnCustomerAddress_<?php echo $customer->getUID(); ?>">
                        <?php echo $customer->getAddress(); ?>
                    </td>
                    <td id="columnCustomerDateCreated_<?php echo $customer->getUID(); ?>">
                        <?php echo $customer->getCreatedAt(); ?>
                    </td>
                    <td id="columnCustomerDateModified_<?php echo $customer->getUID(); ?>">
                        <?php echo $customer->getUpdatedAt(); ?>
                    </td>
                    <td>
                        <a href="#" class="btn btn-primary"
                            onclick="showPage('orders', null, null, null, <?php echo $customer->getUID(); ?>)">View
                            Orders</a>
                        <a href="#" class="btn btn-secondary"
                            onclick="showPage('editCustomer', null, <?php echo $customer->getUID(); ?>)">Edit Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>