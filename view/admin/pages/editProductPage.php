<div id="editProductPage" class="page" style="display: none;">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Product</h1>

        <a href="#" class="btn btn-secondary" onclick="showPage('products')">Back to Products</a>
    </div>

    <div id="editProductFormContainer">
        <form id="editProductForm" action="/api/editProduct" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="editproductID" name="productID">
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="editproductName" name="productName" required>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Price in £</label>
                <input type="number" class="form-control" id="editproductPrice" name="productPrice" min="0.01"
                    step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="productStock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="editproductStock" name="productStock" required>
            </div>
            <div class="mb-3">
                <label for="productDescription" class="form-label">Description</label>
                <textarea class="form-control" id="editproductDescription" name="productDescription"
                    required></textarea>
            </div>
            <div class="mb-3">
                <label for="productCategory" class="form-label">Category</label>
                <select class="form-select" id="editproductCategory" name="productCategory" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>">
                            <?php echo $category['CategoryName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*"
                    onchange="previewImage(this,true)">
            </div>

            <div class="mb-3">
                <img id="editProductimagePreview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px;">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Update Product</button>
                <button type="button" class="btn btn-danger" onclick="deleteProduct()">Delete Product</button>
            </div>
        </form>
    </div>
</div>