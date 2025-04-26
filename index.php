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
      <button onclick="addTask()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">+ Tambah</button>
    </div>

    <div class="flex flex-wrap gap-4 mb-6">
      <button onclick="toggleDarkMode()" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-700">🌙 Dark Mode</button>
      <button onclick="startVoiceInput()" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 dark:bg-purple-700 dark:hover:bg-purple-800">🎙️ Voice Input</button>
      <button onclick="exportExcel()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 dark:bg-green-700 dark:hover:bg-green-800">📋 Export Excel</button>
      <button onclick="exportPDF()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800">📋 Export PDF</button>
      <button onclick="clearAllTasks()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-800">🗑️ Hapus Semua</button>
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
    // Inisialisasi tasks dari localStorage atau array kosong
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    
    // Fungsi untuk menyimpan tasks ke localStorage
    function saveTasks() {
      localStorage.setItem('tasks', JSON.stringify(tasks));
      console.log('Tasks saved:', tasks); // Debugging
    }

    function addTask() {
      const taskName = document.getElementById('taskName').value.trim();
      const taskDesc = document.getElementById('taskDesc').value.trim();
      const startDate = document.getElementById('startDate').value;
      const endDate = document.getElementById('endDate').value;
      const priority = document.getElementById('priority').value;
      const assignTo = document.getElementById('assignTo').value.trim();

      // Validasi input
      if (!taskName || !startDate || !endDate || !assignTo) {
        showToast('Semua field wajib diisi kecuali deskripsi!', 'error');
        return;
      }

      // Validasi tanggal
      if (new Date(endDate) < new Date(startDate)) {
        showToast('Tanggal akhir tidak boleh lebih awal dari tanggal mulai!', 'error');
        return;
      }

      // Buat task baru
      const task = {
        id: Date.now(),
        name: taskName,
        desc: taskDesc,
        start: startDate,
        end: endDate,
        priority: priority,
        assign: assignTo,
        status: 'todo',
        createdAt: new Date().toISOString()
      };

      // Tambahkan task ke array
      tasks.push(task);
      saveTasks();
      renderTasks();

      // Tampilkan notifikasi
      showToast('Tugas berhasil ditambahkan!', 'success');

      // Reset form
      document.getElementById('taskName').value = '';
      document.getElementById('taskDesc').value = '';
      document.getElementById('startDate').value = '';
      document.getElementById('endDate').value = '';
      document.getElementById('priority').selectedIndex = 0;
      document.getElementById('assignTo').value = '';

      // Fokus kembali ke input Nama Tugas
      document.getElementById('taskName').focus();
    }

    function renderTasks() {
      const search = document.getElementById('searchTask').value.toLowerCase();
      const filterAssign = document.getElementById('filterAssign').value;
      const filterPriority = document.getElementById('filterPriority').value;
      
      const todo = document.getElementById('todo');
      const inprogress = document.getElementById('inprogress');
      const done = document.getElementById('done');

      // Kosongkan kolom
      todo.innerHTML = '<h3 class="text-xl font-bold mb-4 dark:text-white">Todo</h3>';
      inprogress.innerHTML = '<h3 class="text-xl font-bold mb-4 dark:text-white">In Progress</h3>';
      done.innerHTML = '<h3 class="text-xl font-bold mb-4 dark:text-white">Done</h3>';

      let assignOptions = new Set();
      let priorityOptions = new Set();

      // Filter dan render tasks
      tasks.forEach(task => {
        const matchesSearch = task.name.toLowerCase().includes(search) || 
                            task.desc.toLowerCase().includes(search);
        const matchesAssign = filterAssign === "" || task.assign === filterAssign;
        const matchesPriority = filterPriority === "" || task.priority === filterPriority;

        if (matchesSearch && matchesAssign && matchesPriority) {
          const taskElement = createTaskElement(task);
          
          if (task.status === 'todo') todo.appendChild(taskElement);
          if (task.status === 'inprogress') inprogress.appendChild(taskElement);
          if (task.status === 'done') done.appendChild(taskElement);
        }
        
        assignOptions.add(task.assign);
        priorityOptions.add(task.priority);
      });

      // Update filter options
      updateFilterOptions('filterAssign', assignOptions, 'Filter Assign...');
      updateFilterOptions('filterPriority', priorityOptions, 'Filter Prioritas...');
    }

    function createTaskElement(task) {
      const div = document.createElement('div');
      const isDarkMode = document.body.classList.contains('bg-gray-900');
      
      // Base class untuk card
      div.className = `p-3 mb-3 rounded cursor-grab task-card border-l-4 ${
        task.priority === 'Prioritas Tinggi' ? 'border-red-500' : 
        task.priority === 'Prioritas Sedang' ? 'border-yellow-500' : 'border-green-500'
      } ${isDarkMode ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-800'}`;
      
      div.draggable = true;
      div.dataset.id = task.id;

      // Format tanggal
      const startDate = new Date(task.start).toLocaleDateString();
      const endDate = new Date(task.end).toLocaleDateString();

      div.innerHTML = `
        <div class="font-bold">${task.name}</div>
        ${task.desc ? `<div class="${isDarkMode ? 'text-gray-300' : 'text-gray-600'} text-sm">${task.desc}</div>` : ''}
        <div class="${isDarkMode ? 'text-gray-300' : 'text-gray-700'} text-xs mt-1">
          <span class="font-semibold">Assign:</span> ${task.assign}
        </div>
        <div class="${isDarkMode ? 'text-gray-300' : 'text-gray-700'} text-xs">
          <span class="font-semibold">Tanggal:</span> ${startDate} - ${endDate}
        </div>
        <div class="${isDarkMode ? 'text-gray-300' : 'text-gray-700'} text-xs">
          <span class="font-semibold">Prioritas:</span> ${task.priority}
        </div>
        <div class="flex justify-end mt-2">
          <button onclick="deleteTask(${task.id})" class="${isDarkMode ? 'text-red-400 hover:text-red-300' : 'text-red-500 hover:text-red-700'} text-xs">Hapus</button>
        </div>
      `;

      div.ondragstart = (e) => {
        e.dataTransfer.setData('text/plain', task.id);
      };

      return div;
    }

    function updateFilterOptions(filterId, options, defaultText) {
      const filter = document.getElementById(filterId);
      const currentValue = filter.value;
      
      filter.innerHTML = `<option value="">${defaultText}</option>`;
      Array.from(options).sort().forEach(option => {
        filter.innerHTML += `<option value="${option}">${option}</option>`;
      });
      
      // Pertahankan nilai yang dipilih sebelumnya jika masih ada
      if (options.has(currentValue)) {
        filter.value = currentValue;
      }
    }

    function setupDragAndDrop() {
      document.querySelectorAll('#todo, #inprogress, #done').forEach(column => {
        column.ondragover = (e) => {
          e.preventDefault();
          column.style.backgroundColor = document.body.classList.contains('bg-gray-900') ? 'rgba(75, 85, 99, 0.5)' : 'rgba(243, 244, 246, 0.5)';
        };
        
        column.ondragleave = () => {
          column.style.backgroundColor = '';
        };
        
        column.ondrop = (e) => {
          e.preventDefault();
          column.style.backgroundColor = '';
          
          const taskId = e.dataTransfer.getData('text/plain');
          const newStatus = column.id;
          
          const taskIndex = tasks.findIndex(t => t.id == taskId);
          if (taskIndex !== -1) {
            tasks[taskIndex].status = newStatus;
            saveTasks();
            renderTasks();
            showToast(`Status tugas diubah ke ${newStatus}`, 'info');
          }
        };
      });
    }

    function deleteTask(taskId) {
      if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
        tasks = tasks.filter(task => task.id != taskId);
        saveTasks();
        renderTasks();
        showToast('Tugas berhasil dihapus!', 'success');
      }
    }

    function clearAllTasks() {
      if (confirm('Apakah Anda yakin ingin menghapus SEMUA tugas? Ini tidak dapat dibatalkan!')) {
        tasks = [];
        saveTasks();
        renderTasks();
        showToast('Semua tugas telah dihapus!', 'success');
      }
    }

    function toggleDarkMode() {
      document.body.classList.toggle('bg-gray-900');
      document.body.classList.toggle('text-white');
      
      // Simpan preferensi dark mode
      const isDark = document.body.classList.contains('bg-gray-900');
      localStorage.setItem('darkMode', isDark);
      
      // Render ulang semua tugas untuk menyesuaikan warna
      renderTasks();
      
      showToast(`Mode ${isDark ? 'Gelap' : 'Terang'} diaktifkan`, 'info');
    }

    function startVoiceInput() {
      if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        showToast('Browser tidak mendukung voice input!', 'error');
        return;
      }
      
      const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
      recognition.lang = 'id-ID';
      recognition.start();
      
      showToast('Mendengarkan...', 'info');
      
      recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        document.getElementById('taskName').value = transcript;
        showToast('Input suara diterima!', 'success');
      };
      
      recognition.onerror = function(event) {
        showToast('Error: ' + event.error, 'error');
      };
    }

    function exportExcel() {
      if (tasks.length === 0) {
        showToast('Tidak ada data untuk diexport!', 'error');
        return;
      }
      
      const data = tasks.map(task => ({
        'Nama Tugas': task.name,
        'Deskripsi': task.desc,
        'Tanggal Mulai': task.start,
        'Tanggal Selesai': task.end,
        'Prioritas': task.priority,
        'Assign': task.assign,
        'Status': task.status
      }));
      
      const ws = XLSX.utils.json_to_sheet(data);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Tugas");
      XLSX.writeFile(wb, "daftar_tugas.xlsx");
      showToast('Export Excel berhasil!', 'success');
    }

    function exportPDF() {
      if (tasks.length === 0) {
        showToast('Tidak ada data untuk diexport!', 'error');
        return;
      }
      
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      
      doc.setFontSize(18);
      doc.text('Daftar Tugas', 10, 10);
      doc.setFontSize(12);
      
      let y = 20;
      tasks.forEach((task, i) => {
        if (y > 280) {
          doc.addPage();
          y = 10;
        }
        
        doc.text(`${i+1}. ${task.name}`, 10, y);
        doc.text(`   Assign: ${task.assign}`, 10, y + 5);
        doc.text(`   Prioritas: ${task.priority}`, 10, y + 10);
        doc.text(`   Status: ${task.status}`, 10, y + 15);
        y += 20;
      });
      
      doc.save("daftar_tugas.pdf");
      showToast('Export PDF berhasil!', 'success');
    }

    function showToast(message, type) {
      const colors = {
        success: '#4f46e5',
        error: '#ef4444',
        info: '#3b82f6',
        warning: '#f59e0b'
      };
      
      Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: colors[type] || colors.info,
        stopOnFocus: true
      }).showToast();
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
      // Cek dark mode
      if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('bg-gray-900', 'text-white');
      }
      
      // Set tanggal default
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('startDate').value = today;
      
      // Setup drag and drop
      setupDragAndDrop();
      
      // Render tasks
      renderTasks();
    });

  </script>

</body>
</html>