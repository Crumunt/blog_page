<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalStatus" aria-hidden="true">
  <?php loadModal($conn) ?>

</div>

<div class="modal fade" id="confirmDelete" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-lg">
    <div class="modal-content">
      <div class="modal-body">
        <p class="">Are you sure you want to delete: <u class="h6 d-block" id="toDeleteName"></u></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="confirmDeleteButton" data-bs-target="#confirmationModal" data-bs-toggle="modal" onclick="deleteRecord(this)">Confirm</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmationModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <p class="h5 text-success text-center" id="confirmMessage"></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-dismiss="modal">Okay</button>
      </div>
    </div>
  </div>
</div>

<?php

function loadModal($conn)
{
  $page = basename($_SERVER['PHP_SELF'], ".php");

  if ($page == 'manage-post') {
?>
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalStatus">Add Post</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetForm('blogs')"></button>
        </div>
        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data" id="formData" onsubmit="event.preventDefault()">
            <div class="form-group mb-3">
              <label for="" class="form-label">Title</label>
              <input type="text" class="form-control" id="blog_title" placeholder="Enter title" required onkeyup="checkDuplication(this)" data-label="blogs">
              <div class="invalid-feedback">
                Blog has already been added.
              </div>
            </div>
            <div class="form-group mb-3" id="thumbnailForm">
              <label for="" class="form-label">Thumbnail</label>
              <input type="file" name="blog_thumbnail" id="thumbnail">
            </div>
            <div class="form-group mb-3">
              <label for="" class="form-label">Category</label>
              <select name="category_name" id="category_name" class="form-select">
                <option value="">Select Category~</option>
                <?php
                $data = showRecords($conn, 'tbl_categories');

                foreach ($data as $category) {
                  echo "<option value=" . $category[1] . ">" . $category[1] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="" class="form-label">Blog Contents</label>
              <div id="toolbar-container"></div>
              <div class="form-group mb-3 border" id="editor" style="min-height: 40vh; max-height: 70vh; overflow-y: scroll;">
              </div>
            </div>
            <div class="button-group d-flex gap-2 justify-content-end">
              <button class="btn btn-warning" data-bs-dismiss="modal" onclick="resetForm('blogs')">Cancel</button>
              <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal" id="editorController" onclick="saveData()" aria-label="blogs">
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
  } elseif ($page == 'manage-categories') {
  ?>
    <div class="modal-dialog modal-dialog-centered" id="categoryModal">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalStatus">Add category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post" id="formData" onsubmit="event.preventDefault()">
            <div class="form-group mb-3">
              <label for="" class="form-label">Category Name</label>
              <input type="text" name="category_name" id="category_name" class="form-control" onkeyup="checkDuplication(this)" data-label="categories">
              <div class="invalid-feedback">
                Category has already been added.
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-bs-dismiss="modal" onclick="resetForm('categories')">Cancel</button>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal" id="editorController" onclick="saveData()" aria-label="categories">
            Save
          </button>
        </div>
      </div>
    </div>
  <?php
  } else {
  ?>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Message #<span id="messageHeader"></span></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-center fs-2 border-bottom" id="concernHeader"></p>
          <p class="messageBody p-2" id="concernBody"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
<?php
  }
}
