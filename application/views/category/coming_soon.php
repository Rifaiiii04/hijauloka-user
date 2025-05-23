<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - HijauLoka</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php $this->load->view('templates/navbar'); ?>

    <div class="container mx-auto px-4 py-16 md:py-24 mt-12">
        <div class="max-w-4xl mx-auto">
            <!-- Hero Section -->
            <div class="text-center mb-12 md:mb-16">
                <div class="relative inline-block mb-8">
                    <div class="absolute inset-0 bg-green-100 rounded-full blur-2xl opacity-50 animate-pulse"></div>
                    <i class="fas fa-seedling text-7xl md:text-8xl text-green-500 relative z-10 animate-bounce-slow"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-green-800 mb-4 animate-fade-in">
                    Coming Soon!
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-2xl mx-auto animate-fade-in-delay">
                    <?php if (isset($category) && $category === 'seeds'): ?>
                        Kategori benih tanaman akan segera hadir. Kami sedang menyiapkan koleksi benih berkualitas untuk Anda.
                    <?php else: ?>
                        Kategori pot tanaman akan segera hadir. Kami sedang menyiapkan koleksi pot cantik untuk tanaman Anda.
                    <?php endif; ?>
                </p>
            </div>
            
            <!-- Notification Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 mb-12 transform hover:scale-[1.02] transition-all duration-300 animate-fade-in-up">
                <div class="max-w-2xl mx-auto">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell text-2xl text-green-600"></i>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-semibold text-green-700 mb-4">Dapatkan Notifikasi</h2>
                        <p class="text-gray-600">Beritahu kami email Anda untuk mendapatkan pemberitahuan ketika kategori ini sudah tersedia.</p>
                    </div>
                    
                    <form class="flex flex-col md:flex-row gap-4 max-w-md mx-auto" id="notifyForm">
                        <div class="flex-1 relative">
                            <input type="email" 
                                   placeholder="Masukkan email Anda" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2">
                            <span>Notifikasi</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-6 animate-fade-in-up-delay">
                <a href="<?= base_url('category/plants') ?>" 
                   class="group flex items-center justify-center gap-3 px-6 py-4 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-300 shadow-md hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-leaf text-xl group-hover:rotate-12 transition-transform"></i>
                    <span class="font-medium">Lihat Tanaman Hias</span>
                </a>
                <a href="<?= base_url() ?>" 
                   class="group flex items-center justify-center gap-3 px-6 py-4 bg-white text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 shadow-md hover:shadow-xl transform hover:-translate-y-0.5 border border-gray-200">
                    <i class="fas fa-home text-xl group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Decorative Elements -->
            <div class="hidden md:block">
                <div class="absolute top-1/4 left-10 opacity-10 animate-float">
                    <i class="fas fa-leaf text-6xl transform rotate-45"></i>
                </div>
                <div class="absolute bottom-1/4 right-10 opacity-10 animate-float-reverse">
                    <i class="fas fa-seedling text-6xl"></i>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('templates/footer'); ?>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(45deg); }
            50% { transform: translateY(-20px) rotate(45deg); }
        }

        @keyframes float-reverse {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(20px); }
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-bounce-slow {
            animation: bounce-slow 3s ease-in-out infinite;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-reverse {
            animation: float-reverse 6s ease-in-out infinite;
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }

        .animate-fade-in-delay {
            animation: fade-in 0.8s ease-out 0.2s forwards;
            opacity: 0;
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
            opacity: 0;
        }

        .animate-fade-in-up-delay {
            animation: fade-in-up 0.8s ease-out 0.4s forwards;
            opacity: 0;
        }

        /* Form validation styles */
        input:invalid {
            border-color: #ef4444;
        }

        input:invalid:focus {
            ring-color: #ef4444;
        }

        /* Success message animation */
        .success-message {
            animation: slide-in 0.5s ease-out forwards;
        }

        @keyframes slide-in {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>

    <script>
        document.getElementById('notifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Show loading state
            const button = this.querySelector('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            button.disabled = true;

            // Simulate API call (replace with actual API call)
            setTimeout(() => {
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'success-message mt-4 p-4 bg-green-100 text-green-700 rounded-xl text-center';
                successMessage.innerHTML = `
                    <i class="fas fa-check-circle mr-2"></i>
                    Terima kasih! Kami akan mengirimkan notifikasi ke ${email} ketika kategori ini tersedia.
                `;
                
                this.appendChild(successMessage);
                
                // Reset form
                this.reset();
                button.innerHTML = originalContent;
                button.disabled = false;

                // Remove success message after 5 seconds
                setTimeout(() => {
                    successMessage.remove();
                }, 5000);
            }, 1500);
        });
    </script>
</body>
</html>
