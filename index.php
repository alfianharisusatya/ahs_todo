<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Tugas</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Library XLSX untuk Export Excel -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <!-- Library jsPDF untuk Export PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <!-- Toastify.js untuk Notifikasi -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

  <div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Tambah / Edit Tugas</h1>

    <div class="flex flex-wrap gap-4 mb-6">
      <input id="taskName" type="text" placeholder="Nama Tugas" class="flex-1 border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
      <input id="taskDesc" type="text" placeholder="Deskripsi" class="flex-1 border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
      <input id="startDate" type="date" class="border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
      <input id="endDate" type="date" class="border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
      <select id="priority" class="border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
        <option value="Prioritas Rendah">Prioritas Rendah</option>
        <option value="Prioritas Sedang">Prioritas Sedang</option>
        <option value="Prioritas Tinggi">Prioritas Tinggi</option>
      </select>
      <input id="assignTo" type="text" placeholder="Assign ke..." class="flex-1 border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
      <button onclick="addTask()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800" aria-label="Tambah Tugas">+ Tambah</button>
    </div>

    <div class="flex flex-wrap gap-4 mb-6">
      <button onclick="toggleDarkMode()" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-700" aria-label="Toggle Dark Mode">🌙 Dark Mode</button>
      <button onclick="startVoiceInput()" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 dark:bg-purple-700 dark:hover:bg-purple-800" aria-label="Voice Input">🎙️ Voice Input</button>
      <button onclick="exportExcel()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 dark:bg-green-700 dark:hover:bg-green-800" aria-label="Export Excel">📋 Export Excel</button>
      <button onclick="exportPDF()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800" aria-label="Export PDF">📋 Export PDF</button>
      <button onclick="clearAllTasks()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-800" aria-label="Hapus Semua Tugas">🗑️ Hapus Semua</button>
    </div>

    <h2 class="text-2xl font-semibold mb-4 dark:text-white">Daftar Tugas</h2>
    <div class="flex gap-4 mb-6">
      <input type="text" id="searchTask" placeholder="Cari tugas..." oninput="renderTasks()" class="flex-1 border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
      <select id="filterAssign" onchange="renderTasks()" class="border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
        <option value="">Filter Assign...</option>
      </select>
      <select id="filterPriority" onchange="renderTasks()" class="border p-2 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
        <option value="">Filter Prioritas...</option>
        <option value="Prioritas Rendah">Prioritas Rendah</option>
        <option value="Prioritas Sedang">Prioritas Sedang</option>
        <option value="Prioritas Tinggi">Prioritas Tinggi</option>
      </select>
    </div>

    <h2 class="text-2xl font-semibold mb-4 dark:text-white">Kanban Board</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow p-4 dark:bg-gray-800" id="todo">
        <h3 class="text-xl font-bold mb-4 dark:text-white">Todo</h3>
      </div>
      <div class="bg-white rounded-lg shadow p-4 dark:bg-gray-800" id="inprogress">
        <h3 class="text-xl font-bold mb-4 dark:text-white">In Progress</h3>
      </div>
      <div class="bg-white rounded-lg shadow p-4 dark:bg-gray-800" id="done">
        <h3 class="text-xl font-bold mb-4 dark:text-white">Done</h3>
      </div>
    </div>
  </div>

  <script>
    // ...existing code...
    // Updated functions for better performance and maintainability
    // ...existing code...
  </script>

</body>
</html>