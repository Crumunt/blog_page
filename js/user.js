const LIKE_BUTTON = document.querySelectorAll('#likeButton')
const HEART_ICON = document.querySelectorAll('#heartIcon')
const LIKE_LABEL = document.querySelectorAll('span.like__label');

if (LIKE_BUTTON.length > 0) {
	LIKE_BUTTON.forEach(button => {
		button.addEventListener('click', () => {

			console.log(button)

			if (button.childNodes[1].textContent == 'favorite') {
				button.childNodes[1].textContent = 'heart_plus'
				button.childNodes[3].textContent = 'Liked'
				button.classList.remove('btn-outline-danger')
				button.classList.add('btn-danger')
				likePost(button.childNodes[3].textContent, button);
				return
			}

			button.classList.remove('btn-danger')
			button.classList.add('btn-outline-danger')

			button.childNodes[1].textContent = 'favorite'
			button.childNodes[3].textContent = 'Like'

			likePost(button.childNodes[3].textContent, button);
		})
	})
}


const CONTENT_WRAPPER = document.getElementById('contentWrapper')

function likePost(likeStatus, button) {

	const LIKE_COUNT = document.querySelector(`p[data-blog-id='${button.getAttribute('aria-label')}']`)

	let data = new FormData();

	data.append('blog_id', button.getAttribute('aria-label'))
	data.append('like_count', Number(LIKE_COUNT.textContent))

	if (likeStatus == 'Liked') {
		data.append('updateLike', 'add');
	} else {
		data.append('updateLike', 'minus');
	}

	let xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			console.log(this.responseText)
			LIKE_COUNT.textContent = this.responseText;
		}
	}

	xhr.open('POST', '/labFiles/blog_page/formHandlers/userHandler.php');

	xhr.send(data);

}

function filterBlogs(option) {

	let xhr = new XMLHttpRequest()

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			CONTENT_WRAPPER.innerHTML = this.responseText
		}
	}

	xhr.open("GET", '/labFiles/blog_page/formHandlers/userHandler.php?filter_type=' + option)

	xhr.send();

}

function searchBlogs(keyword) {


	let xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function () {
		if (this.readyState == 4) {
			CONTENT_WRAPPER.innerHTML = this.responseText
		}
	}

	xhr.open('GET', '/labFiles/blog_page/formHandlers/userHandler.php?keyword=' + keyword)

	xhr.send();

}

function sendMessage() {

	const form = document.getElementById('messageForm')
	const concernMessage = document.querySelector('.concernMessage')
	const MODAL = document.getElementById('staticBackdrop')

	let data = new FormData();

	const concernHeader = document.getElementById('concernHeader').value
	const concernBody = document.getElementById('additionalInformation').value

	data.append('sendMessage', 'send');
	data.append('concern_header', concernHeader)
	data.append('concern_body', concernBody)

	let xhr = new XMLHttpRequest()

	xhr.onreadystatechange = function() {
		if(this.readyState == 4) {
			concernMessage.textContent = this.responseText
			form.reset();
		}
	}

	xhr.open('POST', '/labFiles/blog_page/formHandlers/userHandler.php')

	xhr.send(data)

}

function checkMessageInput(concern) {

	// const concern = document.getElementById('concernHeader').value
	const button = document.getElementById('sendMessageButton')

	if(concern == '') {
		button.setAttribute('disabled', '');
	}else {
		button.removeAttribute('disabled');
	}

}