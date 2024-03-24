<div id="adminsPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admins</h1>

        <a href="#" class="btn btn-secondary" onclick="showPage('addAdmin')">Add Admin</a>
    </div>

    <?php if (isset ($_GET['addAdminError'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_GET['addAdminError']; ?>
        </div>
    <?php elseif (isset ($_GET['addAdminSuccess'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_GET['addAdminSuccess']; ?>
        </div>
    <?php endif; ?>


    <div id="adminUpdate" class="alert" style="display: none;"></div>

    <table id="adminsTable" class="table table-striped table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>Admin ID</th>
                <th>Username</th>
                <th>Date Crwated</th>
                <th>Last Date Modified</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td>
                        <?php echo $admin->getUID(); ?>
                    </td>
                    <td id="columnAdminUsername_<?php echo $admin->getUID(); ?>">
                        <?php echo $admin->getUsername(); ?>
                    </td>
                    <td>
                        <?php echo $admin->getCreatedAt(); ?>
                    </td>
                    <td>
                        <?php echo $admin->getUpdatedAt(); ?>
                    </td>
                    <td>
                        <a href="#" class="btn btn-primary"
                            onclick="showPage('editAdmin', null, null, <?php echo $admin->getUID(); ?>)">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>