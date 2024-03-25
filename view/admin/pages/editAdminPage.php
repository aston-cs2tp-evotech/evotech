<div id="editAdminPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Admin</h1>

        <a href="#" class="btn btn-secondary" onclick="showPage('admins')">Back to Admins</a>
    </div>

    <div id="editAdminForm">
        <form action="/api/editAdmin" method="POST">
            <input type="hidden" id="editadminID" name="adminID">
            <div class="mb-3">
                <label for="adminUsername" class="form-label">Username</label>
                <input type="text" class="form-control" id="editadminUsername" name="adminUsername" required>
            </div>
            <div class="mb-3">
                <label for="adminPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="editadminPassword" name="adminPassword"
                    placeholder="Leave blank to keep the same">
            </div>
            <button type="submit" class="btn btn-primary">Update Admin</button>
        </form>
    </div>
</div>