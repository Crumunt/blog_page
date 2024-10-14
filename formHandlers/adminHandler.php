<?php
require "../db/action.php";

$data = showRecords($conn, 'tbl_blogs');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['loadBlogs'])) {
		generateCards($conn);
	} elseif (isset($_POST['uploadContent'])) {
		uploadContent($conn);
	} elseif (isset($_POST['loadCategories'])) {
		loadCategories($conn);
	} elseif (isset($_POST['loadMessages'])) {
		loadMessages($conn);
	} elseif (isset($_POST['loadContent'])) {
		loadContent($conn);
	} elseif (isset($_POST['updateContent'])) {
		updateContent($conn);
	} elseif (isset($_POST['confirmDelete'])) {
		loadContent($conn);
	} elseif (isset($_POST['finalizeDelete'])) {
		deleteContent($conn);
	}
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	if ($_GET['search']) {
		switch ($_GET['search']) {
			case 'blogs':
				generateCards($conn);
				break;
			case 'categories':
				loadCategories($conn);
				break;
			case 'messages':
				loadMessages($conn);
				break;
		}
	} elseif ($_GET['keyword']) {
		checkDuplication($conn);
	} elseif ($_GET['graphData']) {
		loadGraph($conn);
	}
}

function loadGraph($conn)
{

	try {
		$data = retrieveCount($conn, ['category_name'], 'tbl_blogs', "category_name != ''", 'category_name');
	} catch (Exception $e) {
		echo "ERROR: " . $e;
	}

	foreach ($data as $chartData) {
		$count[] = $chartData[0];
		$label[] = $chartData[1];
	}

	echo json_encode([$count, $label]);
}

function checkDuplication($conn)
{

	$keyword = $_GET['keyword'];

	$tbl = $_GET['tbl_action'];

	if ($tbl == 'blogs') {
		$data = showRecords($conn, 'tbl_blogs', "blog_title LIKE '%$keyword%'");
	} else {
		$data = showRecords($conn, 'tbl_categories', "category_name LIKE '%$keyword%'");
	}

	if ($data) echo "error";
}

function generateCards($conn)
{
	if ($_GET['keyword'] != '') {
		$data = showRecords($conn, 'tbl_blogs', "blog_title LIKE '%{$_GET['keyword']}%' OR category_name LIKE '%{$_GET['keyword']}%'");
	} else {
		$data = showRecords($conn, 'tbl_blogs');
	}

	if (count($data) > 0) {
		foreach ($data as $blog_content) {
?>
			<div class="col-xl-3 col-lg-4 col-md-5 col-sm-10">
				<div class="card shadow">
					<img src="../assets/blog-assets/<?= $blog_content[2] ?>" class="card-img-top" alt="<?= $blog_content[1] ?> Thumbnail" />
					<div class="card-body">
						<h5 class="card-title border-bottom text-truncate"><?= $blog_content[1] ?></h5>
						<p class="card-text text-secondary"><?= $blog_content[4] ?> &bullet; <?= date("M. d, Y", strtotime($blog_content[5])) ?></p>
						<button class="btn btn-warning" value="<?= $blog_content[0] ?>" onclick="loadContent(this)" aria-label="blogs" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
							Edit
						</button>
						<button class="btn btn-danger" value="<?= $blog_content[0] ?>" aria-label="blogs" onclick="confirmMessage(this)" data-bs-toggle="modal" data-bs-target="#confirmDelete">Delete</button>
					</div>
				</div>
			</div>
		<?php
		}
	} else {
		echo "NO DATA FOUND";
	}
}

function uploadContent($conn)
{

	// array with names containing values to skip in foreach loop
	$flagNames = ['uploadContent', 'blog_thumbnail', 'table_label'];

	// array containing names where there is a possibility of content containing a single apostrophe
	$replaceNames = ['blog_content', 'blog_title', 'category_name'];

	$data = [];
	foreach ($_POST as $name => $value) {
		if (!in_array($name, $flagNames)) {
			$data[$name] = (in_array($name, $replaceNames)) ? str_replace("'", "\'", $value) : $value;
		}
	}

	$image = processImage();

	if (!empty($image)) {
		$data['blog_thumbnail'] = $image;
	}

	$table = "tbl_" . $_POST['table_label'];

	try {
		$action = addQuery($conn, $data, $table);
	} catch (Exception $e) {
		echo "Error: " . $e;
	}

	if ($action) {

		if ($_POST['table_label'] == 'blogs') {
			echo "Blog has been added successfully";
		} else {
			echo "Category has been added successfully";
		}

		exit();
	} else {
		echo "There was an error uploading the blog, please try again.";
		exit();
	}
}

function processImage()
{

	// PROCESS IMAGE
	if (!empty($_FILES['blog_thumbnail'] ?? NULL)) {

		// GET NECESSARY FILE INFO
		$fileName = $_FILES['blog_thumbnail']['name'];
		// GET FILE EXTENSION
		$fileExtension = end(explode(".", $fileName));
		// RENAME IMAGE TO Blog_Thumbnail and some random number
		$fileName = "Blog_Thumbnail_" . rand(0, 99999) . "." . $fileExtension;

		$fileTempName = $_FILES['blog_thumbnail']['tmp_name'];

		$uploadDirectory = "../assets/blog-assets/";

		// SET UPLOAD DESTINATION(WHERE FILE WILL END UP IN)
		$uploadDestination = $uploadDirectory . $fileName;


		if (isset($fileName)) {

			$uploadStatus = move_uploaded_file($fileTempName, $uploadDestination);

			if (!$uploadStatus) {
				echo "There was an error uploading the thubmnail";
				exit();
			}
		}

		return $fileName;
	}
}

function loadCategories($conn)
{

	if ($_GET['keyword'] != '') {
		$data = showRecords($conn, 'tbl_categories', "category_name LIKE '%{$_GET['keyword']}%'");
	} else {
		$data = showRecords($conn, 'tbl_categories');
	}



	if (count($data) > 0) {
		$count = 1;
		foreach ($data as $category) {
		?>
			<tr>
				<th class="text-center"><?= $count++ ?></th>
				<th><?= $category[1] ?></th>
				<td>
					<button class="btn btn-warning" value="<?= $category[0] ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="loadContent(this)" aria-label="categories">Edit</button>
					<button class="btn btn-danger" value="<?= $category[0] ?>" onclick="confirmMessage(this)" aria-label="categories" data-bs-toggle="modal" data-bs-target="#confirmDelete">Delete</button>
				</td>
			</tr>

		<?php
		}
	} else {
		echo "No data found";
	}
}

function loadContent($conn)
{

	$contentID = $_POST['content_id'] ?? NULL;

	if ($contentID == NULL) return "Content not Found";

	$table = "tbl_" . $_POST['table_label'];
	// echo $table;

	try {
		$data = showRecords($conn, $table, "id = $contentID");
	} catch (Exception $e) {
		echo "Error: " . $e;
	}

	// turn data into json
	echo json_encode($data);
}

function updateContent($conn)
{

	$content_id = $_POST['content_id'];

	$flagNames = ['updateContent', 'content_id', 'blog_thumbnail', 'table_label'];

	$replaceNames = ['blog_content', 'blog_title', 'category_name'];

	$data = [];

	foreach ($_POST as $name => $value) {
		if (!in_array($name, $flagNames)) {
			$data[$name] = (in_array($name, $replaceNames)) ? str_replace("'", "\'", $value) : $value;
		}
	}

	$image = processImage();

	if (!empty($image)) {
		$data['blog_thumbnail'] = $image;
	}

	$table = "tbl_" . $_POST['table_label'];

	try {
		updateQuery($conn, $data, $table, ['id' => $content_id]);
	} catch (Exception $e) {
		echo "Error: " . $e;
		exit();
	}

	echo "Update Successful";
}

function deleteContent($conn)
{

	$content_id = $_POST['content_id'];

	$table = "tbl_" . $_POST['table_label'];

	try {
		deleteQuery($conn, $table, ['id' => $content_id]);
	} catch (Exception $e) {
		echo 'Error' . $e;
	}

	echo "Content has been successfully deleted";
}

function loadMessages($conn)
{
	if ($_GET['keyword'] != '') {
		$data = showRecords($conn, 'tbl_messages', "concern_header LIKE '%{$_GET['keyword']}%'");
	} else {
		$data = showRecords($conn, 'tbl_messages');
	}

	foreach ($data as $concern) {
		$date = strtotime($concern[3]);
		?>
		<div class="card text-center col-lg-4 col-md-6 col-sm-10" style="width: 18rem;">
			<div class="card-body">
				<h5 class="card-title">Message #<?= $concern[0] ?></h5>
				<h6 class="card-subtitle mb-2 text-muted"><?= $concern[1] ?></h6>
				<p class="card-text"><?= date("M. d, Y", $date) ?></p>
				<button class="btn btn-success mb-1" onclick="loadContent(this)" data-bs-toggle="modal" data-bs-target="#staticBackdrop" value="<?= $concern[0] ?>" aria-label="messages">
					View Message
				</button>
				<button class="btn btn-danger" value="<?= $concern[0] ?>" onclick="confirmMessage(this)" aria-label="messages" data-bs-toggle="modal" data-bs-target="#confirmDelete">Delete Message</button>
			</div>
		</div>
<?php
	}
}
