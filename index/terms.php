<div id="termsModal" class="modal">
  <div class="modal-content">
    <!-- Updated the structure to include a header with dark green background -->
    <div class="header-container">
      <h2 id="termsTitle" style="color: darkgreen;"><b>Faculty Evaluation System <br><br>Terms of Service</b></h2>
    </div>
    <div class="terms-box">
      <p><br>
        Last Updated: March 5, 2024<br><br>

        Please read these Terms of Service carefully before using the Faculty Evaluation System.<br><br>

        By accessing or using the Service, you agree to be bound by these Terms. If you disagree with any part of the terms, then you may not access the Service.<br><br>

        <strong>1. Use of the Service</strong><br>
        • You must be a registered student of <strong id = 'bpctext'>Bulacan Polytechnic College</strong> to use the Service.<br>
        • You are responsible for maintaining the confidentiality of your account and password.<br>
        • You agree not to reproduce, duplicate, copy, sell, resell, 
        or exploit any portion of the Service without express written permission by <strong id = 'bpctext'>Bulacan Polytechnic College</strong>.<br><br>

        <strong>2. User Content</strong><br>
        • Users may submit feedback and evaluations through the Service.<br>
        • By submitting content, you grant <strong id = 'bpctext'>Bulacan Polytechnic College</strong> a non-exclusive, worldwide, royalty-free, irrevocable, sub-licensable license to use, reproduce, adapt, publish, translate, and distribute it.<br><br>

        <strong>3. Privacy Policy</strong><br>
        • Your use of the Service is also governed by our Privacy Policy, which can be found at [Privacy Policy URL].<br><br>

        <strong>4. Termination</strong><br>
        • We may terminate or suspend access to our Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.<br><br>

        <strong>5. Changes</strong><br>
        • We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days' notice.<br><br>

        <strong>6. Contact Us</strong><br>
        • If you have any questions about these Terms, please contact us at [Your Contact Information].
        <br><br>
        After clicking "<strong>Okay</strong>", you'll be redirected to the login page.
        </p>
    </div>
    <div class="btn-box">
      <button id="acceptBtn" class="btn1" onclick="acceptTerms()">Okay</button>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('termsModal');
  var enterNowBtn = document.getElementById('enterNowBtn');
  var declineBtn = document.getElementById('declineBtn');
  var acceptBtn = document.getElementById('acceptBtn');
  var span = document.getElementsByClassName('close')[0];

  enterNowBtn.onclick = function () {
    modal.style.display = 'block';
  };

  span.onclick = function () {
    modal.style.display = 'none';
  };

  declineBtn.onclick = function () {
    modal.style.display = '';
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  };

  acceptBtn.onclick = function () {
    modal.style.display = 'none';
    acceptTerms(); // Call the acceptTerms function
  };
});

function closeModal() {
  var modal = document.getElementById('termsModal');
  modal.style.display = 'none';
}

function acceptTerms() {
  // Handle the acceptance logic here
  // You can add any specific logic you want to execute when the user accepts the terms
  // For example, redirecting to another page
  window.location.href = './login.php';
}

</script>

<style>
  #bpctext{
    color:darkgreen;
  }
  .header-container {
    background-color: whitesmoke; 
    margin: 0px;
    padding: 5px 50px 5px 50px; 
    text-align: center;
    border-radius: 10px;
  }
    #termsContainer {
    background-color: whitesmoke;
    padding: 20px;
    border-radius: 10px;
    color: black;
    margin-bottom: 20px;
    width: 10%;
  }

  /* Styles for Buttons */
  .btn1, .btn2 {
    display: inline-block;
    padding: 10px 20px;
    margin-right: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .btn1 {
    background-color: green;
    color: white;
  }

  .btn2 {
    background-color: gray;
    color: white;
  }

  .btn1:hover {
    background-color: #4CAF50;
  }
  .btn2:hover {
    background-color: lightgray;
  }
  @media only screen and (max-width: 767px) {
    #termsContainer {
        width: 90%;
        margin: 0 auto;
    }
}
    #termsTitle {
        padding: 0px;
        margin: 0px;
        text-align:center;
    }
</style>