<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HomePage</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
      .wishlist {
        color: red !important;
      }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
  <body class="bg-slate-100 overflow-x-hidden font-poppins">
    <!-- Header & Navbar -->
    <header>
      <?php $this->load->view('templates/navbar'); ?>
    </header>
    
    <script>
      function handleLogout(event) {
        event.preventDefault();
        if (confirm("Apakah Anda yakin ingin keluar?")) {
          window.location.href = "<?= base_url('auth/logout'); ?>";
        }
      }
    </script>
  </body>
</html>
