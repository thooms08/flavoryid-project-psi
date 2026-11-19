<style>
    :root {
        --primary-orange:  #ff6a00;
        --manager-green:   #ff6a00;   /* diganti oranye — alias agar kode lama tetap jalan */
        --manager-light:   #fff4e6;   /* diganti light-orange */
        --light-orange:    #fff4e6;
        --text-dark:       #1e293b;
        --text-muted:      #64748b;
    }
    .guide-page { background-color: #f8fafc; min-height: 100vh; padding: 120px 0 80px; }
    .guide-header h1 { font-weight: 800; color: var(--text-dark); letter-spacing: -.5px; }
    .guide-header p   { color: var(--text-muted); font-size: 1.05rem; max-width: 700px; }

    /* Timeline */
    .timeline { position: relative; padding-left: 2rem; margin-bottom: 3rem; list-style: none; }
    .timeline::before {
        content:''; position:absolute; top:0; bottom:0; left:15px;
        width:3px; background:linear-gradient(to bottom, var(--primary-orange), rgba(255,106,0,.1));
        border-radius:5px;
    }
    .timeline-item { position:relative; margin-bottom:2.5rem; padding-left:2.5rem; }
    .timeline-item:last-child { margin-bottom:0; }
    .timeline-icon {
        position:absolute; left:-1.7rem; top:0;
        width:40px; height:40px; background:var(--primary-orange); color:white;
        border-radius:50%; display:flex; align-items:center; justify-content:center;
        font-size:1.2rem; font-weight:bold; border:4px solid #f8fafc;
        box-shadow:0 4px 10px rgba(255,106,0,.3); z-index:2;
    }

    /* Card */
    .guide-card {
        background:#fff; border-radius:1.2rem;
        box-shadow:0 10px 25px rgba(0,0,0,.04); padding:1.5rem 2rem;
        transition:transform .3s,box-shadow .3s;
        border:1px solid rgba(0,0,0,.04);
    }
    .guide-card:hover { transform:translateY(-3px); box-shadow:0 15px 35px rgba(255,106,0,.08); border-color:rgba(255,106,0,.2); }
    .guide-card h2 { font-weight:700; color:var(--text-dark); margin-bottom:1rem; font-size:1.25rem; }

    /* Step list */
    .step-list { list-style:none; padding-left:0; margin-bottom:0; }
    .step-list li { position:relative; padding-left:1.8rem; margin-bottom:.8rem; color:var(--text-muted); }
    .step-list li::before {
        content:'\F26E'; font-family:"bootstrap-icons";
        position:absolute; left:0; top:2px; color:var(--primary-orange); font-size:1.1rem;
    }

    /* Alert */
    .alert-info-box {
        background:var(--manager-light); border:1px dashed var(--primary-orange);
        border-radius:.8rem; padding:1rem;
        display:flex; align-items:flex-start; gap:1rem; margin-top:1.2rem;
    }
    .alert-info-box i   { color:var(--primary-orange); font-size:1.4rem; flex-shrink:0; }
    .alert-info-box p   { margin:0; color:#c2440a; font-weight:600; font-size:.9rem; }
    .alert-info-box span{ font-size:.82rem; color:#c2440a; font-weight:400; }

    /* Closing box */
    .closing-box {
        background:linear-gradient(135deg, var(--primary-orange), #ff8c33);
        border-radius:1.5rem; padding:2.5rem 2rem;
        text-align:center; color:white;
        box-shadow:0 15px 30px rgba(255,106,0,.25);
    }

    /* Nav prev/next */
    .guide-nav {
        display:flex; justify-content:space-between; gap:12px;
        margin-top:2rem; flex-wrap:wrap;
    }
    .guide-nav a {
        display:inline-flex; align-items:center; gap:8px;
        padding:10px 20px; border-radius:50px;
        border:1.5px solid rgba(255,106,0,.3); text-decoration:none;
        color:var(--primary-orange); font-weight:600; font-size:.85rem;
        background:var(--manager-light); transition:all .2s;
    }
    .guide-nav a:hover { background:var(--primary-orange); color:#fff; border-color:var(--primary-orange); }

    @media(max-width:768px){
        .guide-page { padding:100px 0 40px; }
        .guide-card  { padding:1.2rem; }
    }
</style>
