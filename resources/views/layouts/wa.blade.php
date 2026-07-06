<style>
    .floating-wa {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #25D366; /* Warna khas WhatsApp */
        color: #FFF !important;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        text-decoration: none;
        transition: all 0.3s ease;
        opacity: 0; /* Disembunyikan dulu, dimunculkan oleh JS */
        visibility: hidden;
        transform: translateY(20px);
    }

    .floating-wa:hover {
        background-color: #128C7E;
        transform: scale(1.1) translateY(0) !important;
        box-shadow: 0 6px 15px rgba(37, 211, 102, 0.4);
    }

    /* Efek denyut (pulse) agar tombol lebih hidup */
    @keyframes pulse-wa {
        0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
    }

    .floating-wa.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        animation: pulse-wa 2s infinite;
    }
    
    .floating-wa.show:hover {
        animation: none; /* Matikan pulse saat di-hover */
    }
</style>

<a href="https://wa.me/6285797574754?text=Hallo!%20Kak%20Admin,%20aku%20mau%20informasi%20lebih%20lanjut%20soal%20Flavory.id%20donk...." 
   class="floating-wa" 
   id="waButton" 
   target="_blank" 
   rel="noopener noreferrer"
   aria-label="Chat WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const waButton = document.getElementById("waButton");
        
        // Memunculkan tombol dengan delay 500ms setelah halaman dimuat
        setTimeout(() => {
            waButton.classList.add("show");
        }, 500);
    });
</script>