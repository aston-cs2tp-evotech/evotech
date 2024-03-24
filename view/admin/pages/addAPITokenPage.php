<div id="addAPITokenPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add API Token</h1>

        <a href="#" class="btn btn-secondary" onclick="showPage('apiTokens')">Back to API Tokens</a>
    </div>

    <div id="addAPITokenForm">
        <form action="/api/addAPIToken" method="POST">
            <div class="mb-3">
                <label for="tokenName" class="form-label">Token Name</label>
                <input type="text" class="form-control" id="tokenName" name="tokenName" required>
            </div>
            <div class="mb-3">
                <label for="tokenExpiresAt" class="form-label">Expires At</label>
                <input type="datetime-local" class="form-control" id="tokenExpiresAt" name="tokenExpiresAt"
                    min="<?php echo date('Y-m-d\TH:i'); ?>"
                    value="<?php echo date('Y-m-d\TH:i', strtotime('+1 month')); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Add API Token</button>
        </form>
    </div>