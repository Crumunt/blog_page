let editor;

const MODAL = document.getElementById('formData')

if (MODAL.getAttribute('enctype')) {

	MODAL.addEventListener('onload', showEditor())
}

function showEditor() {
	DecoupledEditor
		.create(document.querySelector('#editor'), {
			ckfinder:
			{
				uploadUrl: 'https://ckeditor.com/apps/ckfinder/3.5.0/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
			}

		})
		.then(newEditor => {
			editor = newEditor
			const toolbarContainer = document.querySelector('#toolbar-container');

			toolbarContainer.appendChild(editor.ui.view.toolbar.element);
		})
		.catch(error => {
			console.error(error);
		});
}

window.onload = loadContents()

function loadContents() {

	let contentWrapper = document.getElementById('contentWrapper')

	let contentToBeLoaded = contentWrapper.getAttribute('aria-label')

	let data = new FormData();
	data.append(`load${contentToBeLoaded}`, 'load')

	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		contentWrapper.innerHTML = this.responseText
	}

	xhr.open('POST', '/labFiles/blog_page/formHandlers/adminHandler.php', true)
	xhr.send(data)

}

function getFormData(id) {
	return document.getElementById(id);
}

function checkDuplication(form) {

	let keyword = form.value
	let tbl = form.getAttribute('data-label')

	let button = document.getElementById('editorController');

	let xhr = new XMLHttpRequest()

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			if (this.responseText == "error") {
				form.classList.add('is-invalid')
				button.setAttribute('disabled', '');
			} else {
				form.classList.remove('is-invalid')
				button.removeAttribute('disabled')
			}

			console.log(this.responseText)
		}
	}

	xhr.open('GET', '/labFiles/blog_page/formHandlers/adminHandler.php?keyword=' + keyword + '&tbl_action=' + tbl)

	xhr.send()
}

function saveData() {

	// instantiate new FormData to store key value pairs
	let data = new FormData();

	let tbl_label = getFormData('editorController').getAttribute('aria-label');

	data.append('uploadContent', 'saveData');

	if (tbl_label == 'blogs') {
		let status = getBlogInput(data)

		if(status == 1) return
	} else {
		if(getFormData('category_name').value == '') {
			getFormData('confirmMessage').textContent = 'Something went wrong'
			return
		}
		data.append('category_name', getFormData('category_name').value);
	}

	data.append('table_label', tbl_label)

	// create new XMLHttpRequest
	let xhr = new XMLHttpRequest();

	// check if readystate has changed, run function if so
	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {

			resetForm(tbl_label)
			loadContents();
			getFormData('confirmMessage').textContent = this.responseText;

			console.log(this.responseText)
		}
	}

	// open url to send data
	xhr.open('POST', '/labFiles/blog_page/formHandlers/adminHandler.php', true);

	// send FormData variable
	xhr.send(data)
}

function getBlogInput(data) {

	if(getFormData('blog_title').value == '') {
		return 1;
	}

	// process file input and return relevant information such as the object and its details
	let processedImageFile = processImageFileInput(getFormData('thumbnail'))

	// add key value pairs for each item in the form

	data.append('blog_title', getFormData('blog_title').value)

	if (processedImageFile !== null) {
		data.append('blog_thumbnail', processedImageFile[0], processImageFileInput[1])
	}

	data.append('category_name', getFormData('category_name').value)

	data.append('blog_content', editor.getData())

}


function loadContent(button) {

	let modalHeader = null
	let table_label = null

	let editableContent = button.getAttribute('aria-label');

	if (editableContent == 'blogs') {
		table_label = 'blogs'
		modalHeader = 'Update Blog'
	} else {
		table_label = 'categories'
		modalHeader = 'Update Category'
	}

	let contentID = button.value

	console.log(contentID)

	let data = new FormData()

	data.append("loadContent", "load");
	data.append("content_id", contentID);
	data.append('table_label', editableContent)

	console.log(editableContent)

	let xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {

			let jsonData = this.responseText
			let data = JSON.parse(jsonData)

			if (editableContent != 'messages') {
				getFormData('modalStatus').textContent = modalHeader
				getFormData('editorController').textContent = 'Update';
				getFormData('editorController').value = contentID;
				getFormData('editorController').setAttribute('onclick', 'updateContent(this)')
				getFormData('editorController').setAttribute('aria-label', table_label);
			}

			if (editableContent == 'blogs') {
				getFormData('blog_title').value = data[0][1];
				editor.setData(data[0][3]);
				getFormData('category_name').value = data[0][4];
			} else if (editableContent == 'categories') {
				getFormData('category_name').value = data[0][1]
			} else {
				getFormData('messageHeader').textContent = data[0][0];
				getFormData('concernHeader').textContent = data[0][1]
				getFormData('concernBody').textContent = data[0][2]
			}
		}
	}

	xhr.open("POST", "/labFiles/blog_page/formHandlers/adminHandler.php", true);

	xhr.send(data);

}

function updateContent(button) {

	let contentID = button.value

	let table_label = button.getAttribute('aria-label')

	let data = new FormData()

	data.append('updateContent', 'update');
	data.append('content_id', contentID);

	if (table_label == 'blogs') {
		getBlogInput(data);
	} else {
		data.append('category_name', getFormData('category_name').value)
	}

	data.append('table_label', table_label)

	let xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			getFormData('confirmMessage').textContent = this.responseText
			loadContents();
			resetForm(table_label);
		}
	}

	xhr.open('POST', '/labFiles/blog_page/formHandlers/adminHandler.php', true)

	xhr.send(data)
}

function processImageFileInput(fileInput) {


	if (fileInput.files.length === 0) {
		return null
	}

	let file = fileInput.files[0]

	let fileValues = file.name

	console.log([file, fileValues].length)

	return [file, fileValues]

}

function resetForm(tbl_label) {

	getFormData('formData').reset();

	if (tbl_label == 'blogs') {
		editor.setData('');
	}

	let modalStatus = getFormData('modalStatus')

	console.log(modalStatus.textContent)

	if (modalStatus.textContent == 'Update Blog' || modalStatus.textContent == 'Update Category') {

		if (modalStatus.textContent == 'Update Blog') {
			modalStatus.textContent = 'Add Post'
		} else {
			modalStatus.textContent = 'Add Category'
		}

		getFormData('editorController').textContent = 'Save';
		getFormData('editorController').removeAttribute('value')
		getFormData('editorController').setAttribute('onclick', 'saveData()')
	}

}

function confirmMessage(button) {

	let toDeleteId = button.value;
	let table_label = button.getAttribute('aria-label');

	let data = new FormData();

	data.append('confirmDelete', 'confirm');
	data.append('content_id', toDeleteId);
	data.append('table_label', table_label);

	let xhr = new XMLHttpRequest()

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			let data = JSON.parse(this.responseText)

			getFormData('confirmDeleteButton').setAttribute('value', data[0][0]);
			getFormData('confirmDeleteButton').setAttribute('aria-label', table_label)
			getFormData('toDeleteName').textContent = data[0][1];
		}
	}

	xhr.open("POST", "/labFiles/blog_page/formHandlers/adminHandler.php", true);

	xhr.send(data);

}

function deleteRecord(button) {

	let content_id = button.value
	let table_label = button.getAttribute('aria-label')

	let data = new FormData();

	data.append('finalizeDelete', 'delete')
	data.append('content_id', content_id);
	data.append('table_label', table_label)


	let xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			getFormData('confirmMessage').textContent = this.responseText
			console.log(this.responseText)
			loadContents();
		}
	}

	xhr.open("POST", "/labFiles/blog_page/formHandlers/adminHandler.php", true);

	xhr.send(data);

}

function searchForm(keyword) {

	const content_wrapper = document.getElementById('contentWrapper')

	let xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			// console.log(this.responseText)
			content_wrapper.innerHTML = this.responseText
		}
	}

	xhr.open('GET', '/labFiles/blog_page/formHandlers/adminHandler.php?keyword=' + keyword.value + '&search=' + keyword.getAttribute('aria-label'));

	xhr.send();

}