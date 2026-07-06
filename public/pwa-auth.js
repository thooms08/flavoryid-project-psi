const DB_NAME = 'FlavoryAuthDB';
const STORE_NAME = 'credentials';

// 1. Inisialisasi IndexedDB
function initDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, 1);
        request.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'username' });
            }
        };
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// 2. Hash Password (SHA-256)
async function hashPassword(username, password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(username + '||' + password); // Gunakan username sebagai salt tambahan
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}

// 3. Simpan Kredensial (Saat Online)
async function saveCredential(username, password) {
    const hash = await hashPassword(username, password);
    const db = await initDB();
    const tx = db.transaction(STORE_NAME, 'readwrite');
    const store = tx.objectStore(STORE_NAME);
    store.put({ username: username, hash: hash, last_login: new Date().getTime() });
}

// 4. Cek Kredensial (Saat Offline)
async function checkCredentialOffline(username, password) {
    const hash = await hashPassword(username, password);
    const db = await initDB();
    return new Promise((resolve) => {
        const tx = db.transaction(STORE_NAME, 'readonly');
        const store = tx.objectStore(STORE_NAME);
        const request = store.get(username);
        
        request.onsuccess = () => {
            if (request.result && request.result.hash === hash) resolve(true);
            else resolve(false);
        };
        request.onerror = () => resolve(false);
    });
}