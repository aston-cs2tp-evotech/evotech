<div id="addProductPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add Product</h1>

        <a href="#" class="btn btn-secondary" onclick="showPage('products')">Back to Products</a>
    </div>

    <div id="addProductForm">
        <form action="/api/addProduct" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Price in £</label>
                <input type="number" class="form-control" id="productPrice" name="productPrice" min="0.01" step="0.01"
                    required>
            </div>
            <div class="mb-3">
                <label for="productStock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="productStock" name="productStock" required>
            </div>
            <div class="mb-3">
                <label for="productDescription" class="form-label">Description</label>
                <textarea class="form-control" id="productDescription" name="productDescription" required></textarea>
            </div>
            <div class="mb-3">
                <label for="productCategory" class="form-label">Category</label>
                <select class="form-select" id="productCategory" name="productCategory" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>">
                            <?php echo $category['CategoryName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required
                    onchange="previewImage(this)">
            </div>

            <div class="mb-3">
                <img id="addProductimagePreview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px;">
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

</div>