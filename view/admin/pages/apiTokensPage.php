<div id="apiTokensPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">API Tokens</h1>

        <a href="#" class="btn btn-secondary" onclick="showPage('addAPIToken')">Add API Token</a>
    </div>

    <div id="apiTokenUpdate" class="alert" style="display: none;"></div>

    <table id="apiKeysTable" class="table table-striped table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>AdminID</th>
                <th>Name</th>
                <th>Token</th>
                <th>ExpiresAt</th>
                <th>CreatedAt</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tokens as $token): ?>
                <tr class=<?php $token["AdminID"] ?> id="apiTokensTableRow<?php echo $token["Token"] ?>">
                    <td>
                        <?php echo $token["AdminID"]; ?>
                    </td>
                    <td>
                        <?php echo $token["TokenName"]; ?>
                    </td>
                    <td>
                        <?php echo $token["Token"]; ?>
                    </td>
                    <td>
                        <?php echo $token["ExpiresAt"]; ?>
                    </td>
                    <td>
                        <?php echo $token["CreatedAt"]; ?>
                    </td>
                    <td>
                        <button href="#" class="btn btn-danger"
                            onclick="revokeAPIToken(&quot;<?php echo $token["Token"] ?>&quot;)" <?php if (strcmp($token["Token"], $_SESSION["adminToken"]) == 0) {
                                  echo "disabled";
                              } ?>>Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>