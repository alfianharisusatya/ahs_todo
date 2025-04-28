async function addTask() {
    // Ambil nilai input
    const taskData = {
        name: document.getElementById('taskName').value.trim(),
        desc: document.getElementById('taskDesc').value.trim(),
        start: document.getElementById('startDate').value,
        end: document.getElementById('endDate').value,
        priority: document.getElementById('priority').value,
        assign: document.getElementById('assignTo').value.trim(),
        action: 'add_task'
    };

    // Validasi sederhana
    if (!taskData.name || !taskData.start || !taskData.end || !taskData.assign) {
        alert('Nama tugas, tanggal mulai, tanggal selesai, dan penanggung jawab wajib diisi!');
        return;
    }

    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(taskData)
        });

        const result = await response.json();
        
        if (result.success) {
            alert('Tugas berhasil disimpan dengan ID: ' + result.id);
            // Reset form
            document.getElementById('taskName').value = '';
            document.getElementById('taskDesc').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('assignTo').value = '';
        } else {
            alert('Gagal menyimpan: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan');
    }
}

// Fungsi untuk testing koneksi
async function testConnection() {
    try {
        const response = await fetch('api.php?action=get_tasks');
        const data = await response.json();
        console.log('Data dari database:', data);
        alert('Koneksi berhasil! Lihat data di console');
    } catch (error) {
        console.error('Error:', error);
        alert('Koneksi gagal: ' + error.message);
    }
}

// Panggil ini di console browser untuk testing
window.testConnection = testConnection;