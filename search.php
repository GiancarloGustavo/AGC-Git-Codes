<?php
include 'database/db.php';

if (isset($_POST['search'])) {
    $search = '%' . $_POST['search'] . '%';
    $stmt = $pdo->prepare("SELECT id, title, description FROM codes WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC");
    $stmt->execute([$search, $search]);

    $results = $stmt->fetchAll();
    
    if (count($results) > 0) {
        foreach ($results as $code) {
            echo "<div style='margin-bottom:15px;'>
                    <a href='code-details.php?id={$code['id']}' style='font-weight:bold; font-size:18px; text-decoration:none; color:#fff;'>{$code['title']}</a>
                    <br>
                    <a href='code-details.php?id={$code['id']}' style='text-decoration:none; color:#fff;'>{$code['description']}</a>
                  </div>";
        }
    } else {
        echo "<p style='color: #fff;'>Result not found.</p>";
    }
}
?>
