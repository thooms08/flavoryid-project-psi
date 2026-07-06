const DB_ORDER = 'FlavoryOrderDB';
const DB_VERSION = 1;

// 1. Inisialisasi Database
function initOrderDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_ORDER, DB_VERSION);
        request.onupgradeneeded = (e) => {
            const db = e.target.result;
            // Store untuk meta data (menyimpan last_order_number)
            if (!db.objectStoreNames.contains('order_meta')) {
                db.createObjectStore('order_meta', { keyPath: 'key' });
            }
            // Store untuk antrean pesanan offline
            if (!db.objectStoreNames.contains('offline_orders')) {
                db.createObjectStore('offline_orders', { keyPath: 'id', autoIncrement: true });
            }
        };
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// 2. Fungsi Meta (Nomor Pesanan Terakhir)
async function saveLastOrderNumber(number) {
    const db = await initOrderDB();
    const tx = db.transaction('order_meta', 'readwrite');
    tx.objectStore('order_meta').put({ key: 'last_order_number', value: parseInt(number) || 0 });
}

async function getLastOrderNumber() {
    const db = await initOrderDB();
    return new Promise((resolve) => {
        const tx = db.transaction('order_meta', 'readonly');
        const req = tx.objectStore('order_meta').get('last_order_number');
        req.onsuccess = () => resolve(req.result ? req.result.value : 0);
        req.onerror = () => resolve(0);
    });
}

// 3. Fungsi Antrean Pesanan Offline
async function saveOfflineOrder(orderData) {
    const db = await initOrderDB();
    const tx = db.transaction('offline_orders', 'readwrite');
    tx.objectStore('offline_orders').add(orderData);
}

async function getOfflineOrders() {
    const db = await initOrderDB();
    return new Promise((resolve) => {
        const tx = db.transaction('offline_orders', 'readonly');
        const req = tx.objectStore('offline_orders').getAll();
        req.onsuccess = () => resolve(req.result);
        req.onerror = () => resolve([]);
    });
}

async function clearOfflineOrder(id) {
    const db = await initOrderDB();
    const tx = db.transaction('offline_orders', 'readwrite');
    tx.objectStore('offline_orders').delete(id);
}

// 4. Proses Sinkronisasi Otomatis
async function syncOfflineOrders() {
    const offlineOrders = await getOfflineOrders();
    if (offlineOrders.length === 0) return;

    Swal.fire({
        toast: true, position: 'top-end',
        icon: 'info', title: 'Sinkronisasi data offline...',
        showConfirmButton: false, timerProgressBar: true
    });

    try {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch('/kasir/order/sync', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ orders: offlineOrders })
        });
        
        const result = await res.json();
        
        if (res.ok && result.success) {
            // Hapus data lokal yang berhasil disinkronkan
            for (let order of offlineOrders) {
                await clearOfflineOrder(order.id);
            }
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: `${offlineOrders.length} Pesanan Offline Berhasil Disinkronkan!`,
                showConfirmButton: false, timer: 3000
            }).then(() => window.location.reload());
        }
    } catch (e) {
        console.error("Gagal sinkronisasi data offline:", e);
    }
}