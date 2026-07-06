const ACTION_DB = 'FlavoryActionDB';
const ACTION_VERSION = 1;

// 1. Inisialisasi Database Action
function initActionDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(ACTION_DB, ACTION_VERSION);
        request.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains('offline_actions')) {
                db.createObjectStore('offline_actions', { keyPath: 'id', autoIncrement: true });
            }
        };
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// 2. Simpan Aksi Kasir (Delete, Update, Pay)
async function saveOfflineAction(actionType, orderId, payload = null) {
    const db = await initActionDB();
    const tx = db.transaction('offline_actions', 'readwrite');
    const store = tx.objectStore('offline_actions');
    
    const actionData = {
        action: actionType,
        order_id: orderId,
        payload: payload,
        created_at: new Date().toISOString()
    };
    
    store.add(actionData);
}

// 3. Ambil Semua Aksi Tersimpan
async function getOfflineActions() {
    const db = await initActionDB();
    return new Promise((resolve) => {
        const tx = db.transaction('offline_actions', 'readonly');
        const store = tx.objectStore('offline_actions');
        const req = store.getAll();
        req.onsuccess = () => resolve(req.result);
        req.onerror = () => resolve([]);
    });
}

// 4. Hapus Aksi yang Sudah Terkirim
async function clearOfflineAction(id) {
    const db = await initActionDB();
    const tx = db.transaction('offline_actions', 'readwrite');
    tx.objectStore('offline_actions').delete(id);
}

// 5. Eksekusi Sinkronisasi ke Server
async function syncKasirActions() {
    if (!navigator.onLine) return;

    const actions = await getOfflineActions();
    if (actions.length === 0) return;

    console.log(`Menyinkronkan ${actions.length} aksi kasir...`);

    try {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch('/kasir/sync-actions', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': token, 
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ actions: actions })
        });
        
        const result = await res.json();
        
        if (res.ok && result.success) {
            // Bersihkan antrean aksi yang berhasil dieksekusi server
            for (let act of actions) {
                await clearOfflineAction(act.id);
            }
            
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: 'Sinkronisasi Operasional Selesai!',
                showConfirmButton: false, timer: 3000
            }).then(() => {
                // Reload hanya jika tidak sedang di halaman riwayat order
                if (!window.location.pathname.includes('riwayat')) {
                    window.location.reload();
                }
            });
        }
    } catch (error) {
        console.error("Gagal sync aksi kasir:", error);
    }
}