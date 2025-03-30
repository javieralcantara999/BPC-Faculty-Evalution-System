<!-- signup_terms.php -->
<div id="termsModal" class="modal">
  <div class="modal-content">
    <!-- Updated the structure to include a header with dark green background -->
    <div class="header-container">
      <h2 id="termsTitle" style="color: white;"><b>Terms of Services</b></h2>
    </div>
    <div class="terms-box">
      <p>
        <br>
        Please read these Terms of Service carefully before using the Faculty Evaluation System.<br><br>

        <strong>1. Use of the Service</strong><br>
        
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
    var termsLink = document.getElementById('terms_link');
    var acceptBtn = document.getElementById('acceptBtn');
    var span = document.getElementsByClassName('close')[0];

    // Kapag na-click ang link para sa Terms of Services
    termsLink.onclick = function () {
        modal.style.display = 'block'; // Ipapakita ang modal
    };

    // Kapag na-click ang "Okay" button
    acceptBtn.onclick = function () {
        modal.style.display = 'none'; // Itatago ang modal
        acceptTerms(); // Tawagin ang acceptTerms function
    };

    // Kapag na-click ang "X" button sa modal
    span.onclick = function () {
        modal.style.display = 'none'; // Itatago ang modal
    };

    // Kapag na-click sa labas ng modal
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none'; // Itatago ang modal
        }
    };

});

function closeModal() {
    var modal = document.getElementById('termsModal');
    modal.style.display = 'none'; // Itatago ang modal
}

function acceptTerms() {
    // Dito mo ilalagay ang logic para sa pag-accept ng Terms of Services
    // Halimbawa, pag-activate ng checkbox o pag-redirect sa ibang pahina
}
</script>
<style>
    .modal {
        display: none; /* Itago ang modal sa simula */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4); /* Lagyan ng transparent background */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 1% auto; /* I-center ang modal */
        padding: 10px;
        border: 1px solid #888;
        width: 50%; /* I-adjust ang lapad ng modal */
        border-radius: 10px; /* Bilogin ang mga gilid */
    }

    /* I-style ang header ng modal */
    .header-container {
        background-color: darkgreen; 
        margin: 0px;
        padding: 5px;
        text-align: center;
        border-top-left-radius: 10px; /* Bilogin ang itaas-kaliwang gilid */
        border-top-right-radius: 10px; /* Bilogin ang itaas-kanang gilid */
    }

    /* I-style ang button box ng modal */
    .btn-box {
        text-align: center;
        margin-top: 10px;
    }

    /* I-style ang buttons */
    .btn1 {
        display: inline-block;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
        background-color: green;
        color: white;
    }

    /* I-hover ang button */
    .btn1:hover {
        background-color: #4CAF50;
    }

    /* I-style ang X button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* I-responsibly adjust ang lapad ng modal sa mobile */
    @media only screen and (max-width: 767px) {
        .modal-content {
            width: 90%;
            margin: 15% auto;
        }
    }
</style>