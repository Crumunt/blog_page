<footer class="mt-5">
  <form class="w-50 mx-auto text-white p-3" id="messageForm" onsubmit="event.preventDefault()">
    <!-- Email input -->
    <div class="form-outline mb-4 mt-5">
      <h5 class="text-center text-uppercase fw-bold mb-3">Got Concerns? Send us a message</h5>
      <label class="form-label" for="form6Example5">Concern</label>
      <input type="text" id="concernHeader" onkeyup="checkMessageInput(this.value)" class="form-control" required />
    </div>

    <!-- Message input -->
    <div class="form-outline mb-4">
      <label class="form-label" for="form6Example7">Additional information</label>
      <textarea class="form-control" id="additionalInformation" rows="4"></textarea>
    </div>

    <!-- Submit button -->
    <button class="btn btn-success btn-block mb-4 w-100" id="sendMessageButton" disabled onclick="sendMessage()" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
      Send Message
    </button>
  </form>

  <section class="d-flex justify-content-evenly align-items-center p-2" style="background-color: rgba(0, 0, 0, 0.05);">
    <div class="p-3 text-white">
      © <?= date("Y") ?> Copyright
    </div>
    <div class="social-wrapper d-flex gap-3">
      <a href="https://www.facebook.com" target="_blank">
        <img height="50px" src="<?= ($page == 'index') ? '' : '../' ?>assets/facebook.svg" alt="Facebook Icon">
      </a>
      <a href="https://github.com" target="_blank"><img height="50px" src="<?= ($page == 'index') ? '' : '../' ?>assets/github.svg" alt="Github Icon"></a>
      <a href="https://twitter.com" target="_blank"><img height="50px" src="<?= ($page == 'index') ? '' : '../' ?>assets/x-twitter.svg" alt="Twitter Icon"></a>
    </div>
  </section>

  <!-- MODAL -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalStatus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="messageModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="concernMessage text-center text-uppercase text-success fw-bold"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal"">Okay</button>
      </div>
    </div>
  </div>
  </div>
</footer>


</body>

</html>