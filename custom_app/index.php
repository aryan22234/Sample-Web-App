<?php
// 1. Database Configuration
$host = 'localhost'; $user = 'root'; $pass = ''; $db = 'bkldb'; 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// 2. Logic: Handle Deleting
if (isset($_POST['delete_book'])) {
    $idToDelete = $_POST['book_id'];
    $extensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
    foreach ($extensions as $ext) {
        $filePath = "images/" . $idToDelete . "." . $ext;
        if (file_exists($filePath)) { unlink($filePath); }
    }
    $stmt = $conn->prepare("DELETE FROM books WHERE BookID = ?");
    $stmt->bind_param("i", $idToDelete);
    $stmt->execute();
    header("Location: index.php");
    exit();
}

// 3. Logic: Handle Adding
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title']; $author = $_POST['author'];
    $publisher = $_POST['publisher']; $year = $_POST['pub_year'];
    $stmt = $conn->prepare("INSERT INTO books (Title, Author, Publisher, Pub_year) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $author, $publisher, $year);
    if ($stmt->execute()) {
        $newID = $conn->insert_id; 
        if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] == 0) {
            $extension = pathinfo($_FILES['book_cover']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['book_cover']['tmp_name'], "images/" . $newID . "." . strtolower($extension));
        }
    }
    header("Location: index.php");
    exit();
}

// 4. Logic: Fetch Books (with Search filtering)
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE Title LIKE ? OR Author LIKE ? ORDER BY BookID ASC");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $query = $stmt->get_result();
} else {
    $query = $conn->query("SELECT * FROM books ORDER BY BookID ASC");
}

// 5. Logic: Total Counter
$total_count = $query->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Book Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .library-header { background: #2c3e50; color: white; padding: 2.5rem 0; margin-bottom: 2rem; }
        .book-card { border: none; border-radius: 15px; transition: transform 0.3s ease; height: 100%; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: white; display: flex; flex-direction: column; position: relative; }
        .book-card:hover { transform: translateY(-10px); }
        .img-container { width: 100%; background-color: #ebeef0; border-radius: 15px 15px 0 0; overflow: hidden; }
        .book-img { width: 100%; height: auto; display: block; }
        .badge-id { position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.6); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; z-index: 5; }
        .btn-delete { position: absolute; top: 10px; right: 10px; z-index: 5; opacity: 0; transition: opacity 0.3s; }
        .book-card:hover .btn-delete { opacity: 1; }
        .counter-pill { background: #e67e22; color: white; padding: 4px 15px; border-radius: 50px; font-size: 0.9rem; font-weight: 600; margin-bottom: 15px; display: inline-block; }
    </style>
</head>
<body>

<header class="library-header text-center">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Online Book Library</h1>
        <div class="counter-pill"><?= $total_count ?> Books in Collection</div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-6">
                <form action="index.php" method="GET" class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <?php if(!empty($search)): ?>
                        <a href="index.php" class="btn btn-secondary text-white">Clear</a>
                    <?php endif; ?>
                </form>
                <button class="btn btn-outline-light btn-md w-100" data-bs-toggle="modal" data-bs-target="#addModal">+ Add New Book</button>
            </div>
        </div>
    </div>
</header>

<div class="container mb-5">
    <div class="row g-4">
        <?php if($total_count > 0): ?>
            <?php while($result = $query->fetch_array(MYSQLI_ASSOC)): 
                $id = $result['BookID'];
                
                // CHANGED: Default image path updated to .jpg
                $displayImg = "images/default.jpg"; 
                
                foreach (['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'] as $ext) {
                    if (file_exists("images/$id.$ext")) { 
                        $displayImg = "images/$id.$ext"; 
                        break; 
                    }
                }
            ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card book-card">
                        <span class="badge-id">ID: <?php echo $id; ?></span>
                        <button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $id; ?>">✕</button>
                        
                        <div class="img-container">
                            <img src="<?php echo $displayImg; ?>" class="book-img" alt="Cover">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($result['Title']); ?></h5>
                            <p class="text-muted small mb-3">By <?php echo htmlspecialchars($result['Author']); ?></p>
                            <div class="mt-auto pt-2 border-top small text-secondary">
                                <div><strong>Pub:</strong> <?php echo htmlspecialchars($result['Publisher']); ?></div>
                                <div><strong>Year:</strong> <?php echo htmlspecialchars($result['Pub_year']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="deleteModal<?php echo $id; ?>" tabindex="-1">
                    <div class="modal-dialog modal-sm modal-dialog-centered"><div class="modal-content"><div class="modal-body text-center py-4">
                        <h6>Remove this book?</h6>
                        <form method="POST"><input type="hidden" name="book_id" value="<?php echo $id; ?>"><button type="submit" name="delete_book" class="btn btn-sm btn-danger">Confirm Delete</button></form>
                    </div></div></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5"><h3>No books found matching your search.</h3></div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" enctype="multipart/form-data">
    <div class="modal-header"><h5 class="modal-title">New Entry</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
        <div class="mb-3"><label>Author</label><input type="text" name="author" class="form-control" required></div>
        <div class="mb-3"><label>Publisher</label><input type="text" name="publisher" class="form-control"></div>
        <div class="mb-3"><label>Year</label><input type="number" name="pub_year" class="form-control"></div>
        <div class="mb-3"><label>Cover Image (Optional)</label><input type="file" name="book_cover" class="form-control" accept=".jpg,.jpeg,.png"></div>
    </div>
    <div class="modal-footer"><button type="submit" name="add_book" class="btn btn-primary w-100">Save Book</button></div>
</form></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>