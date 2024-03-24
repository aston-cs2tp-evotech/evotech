<div id="editCustomerPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Customer</h1>
        <a href="#" class="btn btn-secondary" onclick="showPage('customers')">Back to Customers</a>
    </div>

    <div id="editCustomerForm">
        <form>
            <input type="hidden" id="editcustomerID" name="customerID">
            <div class="mb-3">
                <label for="customerEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="editcustomerEmail" name="customerEmail" required>
            </div>
            <div class="mb-3">
                <label for="customerUsername" class="form-label">Username</label>
                <input type="text" class="form-control" id="editcustomerUsername" name="customerUsername" required>
            </div>
            <div class="mb-3">
                <label for="customerAddress" class="form-label">Address</label>
                <textarea class="form-control" id="editcustomerAddress" name="customerAddress" required></textarea>
            </div>
            <div class="mb-3">
                <label for="customerPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="editcustomerPassword" name="customerPassword"
                    placeholder="Leave blank to keep the same">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Customer</button>
                <button type="button" class="btn btn-danger" onclick="deleteCustomer()">Delete Customer</button>
            </div>
        </form>
    </div>
</div>