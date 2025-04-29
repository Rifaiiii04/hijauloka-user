<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HomePage</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script
			type="module"
			src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"
		></script>
    <style>
      .wishlist {
        color: red !important;
      }
      
      /* Loader Styles */
      .loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
      }
      
      .loader {
        position: relative;
        width: 120px;
        height: 120px;
      }

      .loader::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 20px;
        height: 20px;
        background: #166534;
        border-radius: 50%;
        animation: grow 1s ease-in-out infinite;
      }

      .loader::after {
        content: '🌱';
        font-size: 24px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      }

      @keyframes grow {
        0% {
          transform: translateX(-50%) scale(0);
          opacity: 0;
        }
        50% {
          transform: translateX(-50%) scale(1);
          opacity: 1;
        }
        100% {
          transform: translateX(-50%) scale(0);
          opacity: 0;
        }
      }

      /* Logout Modal Styles */
      .logout-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
      }

      .logout-modal-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        text-align: center;
        max-width: 400px;
        width: 90%;
        transform: scale(0.9);
        opacity: 0;
        transition: all 0.3s ease;
      }

      .logout-modal.active .logout-modal-content {
        transform: scale(1);
        opacity: 1;
      }
    </style>
  </head>
  <body class="bg-slate-100 overflow-x-hidden font-poppins">
    <!-- Add Loader HTML -->
    <div id="logoutLoader" class="loader-container">
      <div class="loader"></div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="logout-modal">
      <div class="logout-modal-content">
        <div class="text-center mb-6">
          <i class="fas fa-sign-out-alt text-4xl text-green-800 mb-4"></i>
          <h3 class="text-2xl font-semibold text-gray-900">Logout Confirmation</h3>
          <p class="text-gray-600 mt-2">Are you sure you want to leave HijauLoka?</p>
        </div>
        <div class="flex gap-3 justify-center">
          <button onclick="confirmLogout()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">
            Yes, Logout
          </button>
          <button onclick="closeLogoutModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
            Cancel
          </button>
        </div>
      </div>
    </div>
    <!-- Header & Navbar -->
    <header>
      <?php $this->load->view('templates/navbar'); ?>
    </header>
    
    <script>
      function handleLogout(event) {
        event.preventDefault();
        const modal = document.getElementById('logoutModal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
      }

      function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
      }

      function confirmLogout() {
        const loader = document.getElementById('logoutLoader');
        const modal = document.getElementById('logoutModal');
        
        modal.style.display = 'none';
        loader.style.display = 'flex';
        
        setTimeout(() => {
          window.location.href = "<?= base_url('auth/logout'); ?>";
        }, 2000);
      }

      // Close modal when clicking outside
      document.getElementById('logoutModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeLogoutModal();
        }
      });
    </script>
  </body>
</html>
