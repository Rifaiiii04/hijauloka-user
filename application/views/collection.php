<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collection</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/output.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body class="bg-slate-100 min-h-screen">
    <?php $this->load->view('templates/header'); ?>

    <div class="container mx-auto mt-28 px-4">
        <h1 class="text-3xl font-bold text-green-800 mb-6 flex items-center">
            <i class="fas fa-leaf mr-3"></i> Collection
        </h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="<?= base_url('collection/plants') ?>" class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center hover:bg-green-50 transition">
                <i class="fas fa-seedling text-4xl text-green-700 mb-3"></i>
                <span class="text-xl font-semibold text-green-800">Plants</span>
            </a>
            <a href="<?= base_url('collection/seeds') ?>" class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center hover:bg-green-50 transition">
                <i class="fas fa-wheat-awn text-4xl text-yellow-700 mb-3"></i>
                <span class="text-xl font-semibold text-green-800">Seeds</span>
            </a>
            <a href="<?= base_url('collection/pots') ?>" class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center hover:bg-green-50 transition">
                <i class="fas fa-box text-4xl text-gray-700 mb-3"></i>
                <span class="text-xl font-semibold text-green-800">Pots</span>
            </a>
        </div>
    </div>
</body>
</html>