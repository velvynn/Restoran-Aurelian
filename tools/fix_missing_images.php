<?php
// Auto-fix missing images: maps products to available image files
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'show';
$root = realpath(__DIR__ . '/..');

// Get all available images
$img_dir = $root . '/assets/img';
$available_images = [];
if (is_dir($img_dir)) {
    foreach (scandir($img_dir) as $file) {
        if ($file !== '.' && $file !== '..' && is_file($img_dir . '/' . $file)) {
            $available_images[] = $file;
        }
    }
}
sort($available_images);

// Get all products
$db->query('SELECT id, name, image FROM products ORDER BY id');
$all_products = $db->resultSet();

// Find missing
$missing_map = [];
$img_counter = 0;

foreach ($all_products as $product) {
    $img = trim($product['image'] ?? '');
    
    if ($img === '') {
        continue;
    }

    $filename = basename($img);
    $exists = file_exists($img_dir . '/' . $filename);

    if (!$exists) {
        // Map to next available image
        $new_img = $available_images[$img_counter % count($available_images)];
        $missing_map[$product['id']] = [
            'name' => $product['name'],
            'old' => $img,
            'new' => $new_img
        ];
        $img_counter++;
    }
}

// Apply fix if requested
if ($action === 'apply' && !empty($missing_map)) {
    foreach ($missing_map as $prod_id => $data) {
        $db->query('UPDATE products SET image = :image WHERE id = :id');
        $db->bind(':image', $data['new']);
        $db->bind(':id', $prod_id);
        $db->execute();
    }
    $action = 'done';
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auto-Fix Missing Images</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #0b3b2e; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .old { color: #c00; text-decoration: line-through; }
        .new { color: #0a0; font-weight: bold; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .btn-primary { background: #0b3b2e; color: white; }
        .btn-primary:hover { background: #0f5132; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .status { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .status-ok { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-warn { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-done { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .summary { font-weight: bold; margin: 20px 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin: 20px 0; }
        .img-thumb { width: 100%; height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🖼️ Auto-Fix Missing Product Images</h2>
        
        <?php if ($action === 'done'): ?>
            <div class="status status-done">
                ✓ Fixed! Updated <?php echo count($missing_map); ?> product(s) to use available images.<br>
                <a href="check_missing_images.php" class="btn btn-primary" style="margin-top: 10px;">View Details</a>
            </div>
        <?php endif; ?>

        <div class="summary">
            Available images: <?php echo count($available_images); ?> file(s)<br>
            Products with missing images: <?php echo count($missing_map); ?> product(s)
        </div>

        <?php if (empty($missing_map)): ?>
            <div class="status status-ok">
                ✓ All products have valid images! Nothing to fix.
            </div>
        <?php else: ?>
            <div class="status status-warn">
                ⚠️ Found <?php echo count($missing_map); ?> product(s) with missing image files.
                Click "Apply Fix" below to auto-assign them to available images.
            </div>

            <h3>Updates to be made:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Current Image</th>
                    <th>New Image</th>
                </tr>
                <?php foreach ($missing_map as $id => $data): ?>
                <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php echo htmlspecialchars($data['name']); ?></td>
                    <td><span class="old"><?php echo htmlspecialchars($data['old']); ?></span></td>
                    <td><span class="new"><?php echo htmlspecialchars($data['new']); ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <form method="GET">
                <input type="hidden" name="action" value="apply">
                <button type="submit" class="btn btn-success">✓ Apply Fix (Update <?php echo count($missing_map); ?> Products)</button>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary">Cancel</a>
            </form>
        <?php endif; ?>

        <hr style="margin: 30px 0;">
        <h3>Available Images (<?php echo count($available_images); ?>)</h3>
        <div class="grid">
            <?php foreach ($available_images as $img): ?>
                <div style="text-align: center;">
                    <img src="<?php echo SITE_URL; ?>assets/img/<?php echo rawurlencode($img); ?>" alt="<?php echo htmlspecialchars($img); ?>" class="img-thumb">
                    <small><?php echo htmlspecialchars($img); ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <hr style="margin: 30px 0;">
        <a href="../customer/menu.php" class="btn btn-primary">← Back to Menu</a>
    </div>
</body>
</html>
