<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>evotech; Setup</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="/oobe/style.css" rel="stylesheet">
</head>
<body>
    <div id="container" class="container">
        <h2>evotech; setup</h2>
        <div id="page1" class="page active">
            <p>Welcome to evotech; setup. This wizard will guide you through the setup process.</p>
            <p>Click the "Next" button to continue.</p>
        </div>

        <!-- Page 2: Database Connection Settings -->
        <div id="page2" class="page">
            <div id="connectionStatus" class="alert" role="alert" display="none"></div>
            <p>Enter your database connection settings below.</p>
            <form id="dbConfigForm">
                <div class="mb-3">
                    <label for="dbUsername" class="form-label">DB Username</label>
                    <input type="text" class="form-control" id="dbUsername" value="root">

                    <label for="dbPassword" class="form-label">DB Password</label>
                    <input type="password" class="form-control" id="dbPassword" value="">

                    <label for="dbName" class="form-label">DB Name</label>
                    <input type="text" class="form-control" id="dbName" value="root_db">

                    <label for="dbHost" class="form-label">DB Host</label>
                    <input type="text" class="form-control" id="dbHost" value="localhost">
                </div>
            </form>
        </div>

        <!-- Page 3: Create Database -->
        <div id="page3" class="page">
            <div id="createDBStatus" class="alert" role="alert" display="none"></div>
            <form id="createDBForm">
                <p><b>Create Database</b></p>   
                <p>Tick the checkbox below to import dummy data into the database for testing</p>
                <div class="form-checkmb-3 form-control form-control-lg">
                    <input class="form-check-input" type="checkbox" value="" id="dummyDataCheckbox">
                    <label class="form-check-label" for="dummyDataCheckbox">
                        Import dummy data
                    </label>
                </div>
            </form>
        </div>

        <!-- Page 4: Configure Admin -->
        <div id="page4" class="page">
            <div id="createAdminStatus" class="alert" role="alert" display="none"></div>
            <form id="adminConfigForm">
                <div class="mb-3">
                    <label for="adminUsername" class="form-label">Admin Username</label>
                    <input type="text" class="form-control" id="adminUsername" value="admin">

                    <label for="adminPassword" class="form-label">Admin Password</label>
                    <input type="password" class="form-control" id="adminPassword" value="">

                    <label for="adminConfirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="adminConfirmPassword" value="">
                </div>
            </form>
        </div>

        <!-- Page 5: Finish Setup -->
        <div id="page5" class="page">
            <div id="finishSetupStatus" class="alert" role="alert" display="none"></div>
            <p>Setup will be completed once you click the "Finish Setup" button.</p>
            <p>You will be redirected to the admin login page.</p>
        </div>

        <div class="bottomButtons">
            <div class="button-left">
                <!-- Link to GitHub repo -->
                <a href="https://github.com/aston-cs2tp-evotech/evotech" target="_blank" class="btn btn-outline-primary">GitHub Repo</a>
                <a href="https://discord.gg/JrhuYPEQmR" target="_blank" class="btn btn-outline-primary">Discord</a>
            </div>

            <div class="button-right">
                <button id="nextBtn" class="btn btn-primary active">Next Page</button>
                <button id="checkConnectionBtn" class="btn btn-outline-primary">Check Connection</button>
                <button id="createDBBtn" class="btn btn-outline-primary">Create Database</button>
                <button id="createAdminBtn" class="btn btn-outline-primary">Create Admin</button>
                <button id="finishSetupBtn" class="btn btn-primary">Finish Setup</button>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentPage = 1;

            // Add event listener for the "Next Page" button
            $("#nextBtn").click(function() {
                // Hide the current page
                $("#page" + currentPage).removeClass("active");

                // Increment the current page number
                currentPage++;

                // Show the next page
                $("#page" + currentPage).addClass("active");

                switch (currentPage) {
                    case 2:
                        $("#nextBtn").hide();
                        $("#checkConnectionBtn").show();
                        break;
                    case 3:
                        $("#nextBtn").hide();
                        $("#createDBBtn").show();
                        break;
                    case 4:
                        $("#nextBtn").hide();
                        $("#createAdminBtn").show();
                        break;
                    case 5:
                        $("#nextBtn").hide();
                        $("#finishSetupBtn").show();
                        break;
                }
            });

            // Add event listener for the "Check Connection" button
            $("#checkConnectionBtn").click(function() {
                var dbUsername = $("#dbUsername").val();
                var dbPassword = $("#dbPassword").val();
                var dbName = $("#dbName").val();
                var dbHost = $("#dbHost").val();

                $.ajax({
                    url: "/oobe/checkDBConnection",
                    type: "POST",
                    data: {
                        dbUsername: dbUsername,
                        dbPassword: dbPassword,
                        dbName: dbName,
                        dbHost: dbHost
                    },
                    success: function(response) {
                        $("#connectionStatus").display = "block";
                        $("#connectionStatus").removeClass("alert-danger");
                        $("#connectionStatus").addClass("alert-success");
                        $("#connectionStatus").text("Database connection successful. Go to Next Page to continue.");

                        // Hide the "Check Connection" button
                        $("#checkConnectionBtn").hide();

                        // Show the "Next Page" button
                        $("#nextBtn").show();
                    },
                    error: function(xhr, status, error) {
                        $("#connectionStatus").display = "block";
                        $("#connectionStatus").removeClass("alert-success");
                        $("#connectionStatus").addClass("alert-danger");
                        $("#connectionStatus").text(xhr.responseText);
                    }
                });
            });

            // Add event listener for the "Create Database" button
            $("#createDBBtn").click(function() {
                var dummyData = $("#dummyDataCheckbox").is(":checked");

                $.ajax({
                    url: "/oobe/setupDatabase",
                    type: "POST",
                    data: {
                        dummyData: dummyData
                    },
                    success: function(response) {
                        $("#createDBStatus").display = "block";
                        $("#createDBStatus").removeClass("alert-danger");
                        $("#createDBStatus").addClass("alert-success");
                        $("#createDBStatus").text("Database created successfully. Go to Next Page to continue.");

                        // Hide the "Create Database" button
                        $("#createDBBtn").hide();

                        // Show the "Next Page" button
                        $("#nextBtn").show();
                    },
                    error: function(xhr, status, error) {
                        $("#createDBStatus").display = "block";
                        $("#createDBStatus").removeClass("alert-success");
                        $("#createDBStatus").addClass("alert-danger");
                        $("#createDBStatus").text(xhr.responseText);
                    }
                });
            });

            // Add event listener for the "Create Admin" button
            $("#createAdminBtn").click(function() {
                var adminUsername = $("#adminUsername").val();
                var adminPassword = $("#adminPassword").val();
                var adminConfirmPassword = $("#adminConfirmPassword").val();

                if (adminPassword != adminConfirmPassword) {
                    $("#createAdminStatus").display = "block";
                    $("#createAdminStatus").removeClass("alert-success");
                    $("#createAdminStatus").addClass("alert-danger");
                    $("#createAdminStatus").text("Passwords do not match.");
                    return;
                }

                $.ajax({
                    url: "/oobe/setupAdmin",
                    type: "POST",
                    data: {
                        adminUsername: adminUsername,
                        adminPassword: adminPassword
                    },
                    success: function(response) {
                        $("#createAdminStatus").display = "block";
                        $("#createAdminStatus").removeClass("alert-danger");
                        $("#createAdminStatus").addClass("alert-success");
                        $("#createAdminStatus").text("Admin account created successfully. Go to Next Page to continue.");

                        // Hide the "Create Admin" button
                        $("#createAdminBtn").hide();

                        // Show the "Next Page" button
                        $("#nextBtn").show();
                    },
                    error: function(xhr, status, error) {
                        $("#createAdminStatus").display = "block";
                        $("#createAdminStatus").removeClass("alert-success");
                        $("#createAdminStatus").addClass("alert-danger");
                        $("#createAdminStatus").text(xhr.responseText);
                    }
                });
            });

            // Add event listener for the "Finish Setup" button
            $("#finishSetupBtn").click(function() {
                $.ajax({
                    url: "/oobe/finishSetup",
                    type: "POST",
                    success: function(response) {
                        window.location.href = "/admin";
                    },
                    error: function(xhr, status, error) {
                        $("#finishSetupStatus").display = "block";
                        $("#finishSetupStatus").removeClass("alert-success");
                        $("#finishSetupStatus").addClass("alert-danger");
                        $("#finishSetupStatus").text(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
