<?php
session_start();
// Se l'utente ha già fatto il login, lo mando direttamente alla dashboard!
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitgram</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@1,400;1,500&display=swap" rel="stylesheet">
    <style>
        :root {
            --rosa-carne: #efd3d2;
            --beige-chiaro: #f8f5f0;
            --pure-white: #ffffff;
            --text-main: #3d3d3d;
            --text-muted: #888888;
            --accent-dark: #d8b4b2;
            --gray-border: #f0e4e2;
            --accent-pop: #b8807d;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--beige-chiaro);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Navbar */
        nav {
            background-color: var(--pure-white);
            padding: 0.8rem 2%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--rosa-carne);
        }

        .nav-left { display: flex; align-items: center; gap: 15px; flex: 1; }
        .menu-container { position: relative; }
        .hamburger { font-size: 1.6rem; cursor: pointer; padding: 5px; }
        .elegant-tagline { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.25rem; color: var(--accent-dark); }

        .dropdown-menu {
            display: none; position: absolute; left: 0; top: 100%; margin-top: 12px;
            background-color: var(--pure-white); min-width: 220px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid var(--rosa-carne);
            border-radius: 8px; z-index: 1001;
        }
        .dropdown-menu::before { content: ''; position: absolute; top: -12px; left: 0; width: 100%; height: 12px; }
        .menu-container:hover .dropdown-menu { display: block; }
        .dropdown-menu a {
            color: var(--text-main); padding: 14px 20px; text-decoration: none; display: block;
            font-size: 0.85rem; border-bottom: 1px solid var(--beige-chiaro); transition: all 0.2s ease;
        }
        .dropdown-menu a:hover { background-color: var(--beige-chiaro); padding-left: 25px; }

        /* --- BARRA DI RICERCA CON LENTE --- */
        .search-container {
            flex: 2;
            display: flex;
            justify-content: center;
            position: relative;
            max-width: 350px;
            margin: 0 auto;
        }
        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            opacity: 0.6;
            pointer-events: none;
        }
        .search-bar {
            width: 100%;
            padding: 10px 18px 10px 48px;
            border-radius: 20px;
            border: 1px solid var(--rosa-carne);
            outline: none;
            font-family: inherit;
            font-size: 0.85rem;
            background-color: var(--beige-chiaro);
            transition: all 0.3s ease;
        }
        .search-bar:focus { background-color: var(--pure-white); border-color: var(--accent-dark); }

        .nav-links { flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 15px; }
        .nav-links a.text-link { text-decoration: none; color: var(--text-main); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .header-profile-link { text-decoration: none; display: flex; align-items: center; margin-left: 10px; }
        .header-avatar {
            width: 32px; height: 32px; background-color: var(--beige-chiaro); border: 1px solid var(--rosa-carne);
            border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;
        }
        .header-avatar:hover { background-color: var(--rosa-carne); transform: scale(1.05); }

        /* Layout Lookbook */
        .lookbook-container {
            max-width: 1200px;
            margin: 2rem auto 0 auto;
            padding: 0 1.5rem 4rem 1.5rem;
        }

        /* --- TASTI FISSATI ALLO SCHERMO --- */
        .add-look-btn {
            position: fixed;
            bottom: 30px;
            right: 40px;
            z-index: 999;
            color: var(--accent-pop);
            font-size: 5.5rem;
            font-weight: 300;
            text-decoration: none;
            transition: all 0.3s ease;
            line-height: 0.8;
            text-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }

        .add-look-btn:hover {
            transform: scale(1.15) rotate(90deg);
            color: var(--text-main);
            text-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        /* --- TASTO ARMADIO PULITO --- */
        .wardrobe-btn {
            position: fixed;
            bottom: 120px;
            right: 44px; /* Centrato col tasto + */
            z-index: 998;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
            background: transparent !important; /* Forza l'assenza di sfondo */
            border: none !important;
            box-shadow: none !important;
        }

        .wardrobe-btn img {
            width: 45px;
            height: auto;
            opacity: 0.8;
            background: transparent !important; /* Forza l'assenza di sfondo sull'immagine */
            transition: all 0.3s ease;
            /* Ho tolto mix-blend-mode che rovinava l'immagine già trasparente */
        }

        .wardrobe-btn:hover img {
            transform: translateY(-4px) scale(1.1);
            opacity: 1;
        }

        /* Filtri di Stile Centrali */
        .style-navigation {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .style-pill {
            background: transparent;
            border: 1px solid var(--text-muted);
            color: var(--text-muted);
            padding: 8px 20px;
            border-radius: 25px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .style-pill:hover, .style-pill.active {
            border-color: var(--text-main);
            color: var(--text-main);
            background-color: var(--pure-white);
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }

        /* Griglia Mosaico */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 20px;
        }

        .look-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background-color: var(--rosa-carne);
            aspect-ratio: 3/4;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .look-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(216, 180, 178, 0.4);
        }

        .look-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pure-white);
            font-weight: 300;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        .look-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 18px 12px;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
            color: var(--pure-white);
            box-sizing: border-box;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .look-card:hover .look-overlay { opacity: 1; }
        .overlay-user { font-weight: 600; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; }
        .overlay-mini-avatar { width: 18px; height: 18px; background-color: var(--pure-white); border-radius: 50%; }

        footer {
            padding: 3rem 2rem;
            text-align: center;
            font-size: 0.7rem;
            letter-spacing: 2px;
            color: var(--text-muted);
            text-transform: uppercase;
            border-top: 1px solid var(--gray-border);
            margin-bottom: 20px;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .search-container { display: none; }
            .elegant-tagline { display: none; }
            .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }
            .look-overlay { opacity: 1; background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 100%); }

            /* Aggiustamento tasti per mobile */
            .add-look-btn { right: 20px; bottom: 20px; font-size: 4.5rem; }
            .wardrobe-btn { right: 24px; bottom: 95px; } /* Riallineato per mobile */
            .wardrobe-btn img { width: 35px; }
        }

        /* --- OVERLAY E SIDEBAR PROFILO --- */
        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1005; /* Sopra la navbar */
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .profile-sidebar {
            position: fixed;
            top: 0;
            right: -350px; /* Nasconde il pannello fuori dallo schermo a destra */
            width: 320px;
            height: 100vh;
            background-color: var(--pure-white);
            z-index: 1010; /* Sopra l'overlay */
            box-shadow: -5px 0 25px rgba(0,0,0,0.1);
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .profile-sidebar.open {
            right: 0; /* Fa scorrere il pannello dentro lo schermo */
        }

        /* Contenuti Sidebar */
        .sidebar-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px; border-bottom: 1px solid var(--gray-border);
        }

        .sidebar-header h2 { margin: 0; font-size: 1.2rem; font-weight: 500; }

        .close-sidebar-btn {
            background: none; border: none; font-size: 2rem; cursor: pointer;
            color: var(--text-muted); line-height: 1; transition: color 0.3s;
        }

        .close-sidebar-btn:hover { color: var(--accent-pop); }

        .sidebar-content { padding: 30px 20px; text-align: center; }

        .sidebar-avatar-large {
            width: 90px; height: 90px; background-color: var(--beige-chiaro);
            border: 2px solid var(--accent-dark); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem; margin: 0 auto 15px auto;
        }

        .sidebar-username { margin: 0 0 10px 0; font-size: 1.2rem; color: var(--text-main); }
        .sidebar-bio { font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 25px; }

        .sidebar-stats {
            display: flex; justify-content: space-around; margin-bottom: 30px;
            padding: 15px 0; border-top: 1px solid var(--gray-border); border-bottom: 1px solid var(--gray-border);
        }

        .sidebar-stats div { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; }
        .sidebar-stats strong { font-size: 1.2rem; color: var(--text-main); }

        .edit-profile-btn {
            display: block; width: 100%; padding: 14px 0;
            background-color: var(--accent-pop); color: var(--pure-white);
            text-decoration: none; border-radius: 30px; font-weight: 500;
            font-size: 0.9rem; transition: background-color 0.3s ease;
            box-sizing: border-box;
        }

        .edit-profile-btn:hover { background-color: var(--text-main); }

        /* Responsive Sidebar */
        @media (max-width: 400px) {
            .profile-sidebar { width: 100%; right: -100%; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        <div class="menu-container">
            <div class="hamburger">☰</div>
            <div class="dropdown-menu">
                <a href="#">Esplora Tendenze</a>
                <a href="Admin/premium.php">Premium</a>
                <a href="impostazioni.php">Impostazioni</a>
            </div>
        </div>
        <div class="elegant-tagline">be fit. be style.</div>
    </div>

    <div class="search-container">
        <img src="images/LenteDiIngrandimento.png" alt="Cerca" class="search-icon">
        <input type="text" class="search-bar" placeholder="Cerca stili, capi o creator...">
    </div>

    <div class="nav-links">
        <a href="Admin/login.php" class="text-link">Accedi</a>
        <a href="Admin/registrazione.php" class="text-link" style="color: var(--accent-dark);">Registrati</a>
        <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo" style="cursor: pointer;">
            <div class="header-avatar">👤</div>
        </div>
    </div>
</nav>

<a href="#" class="wardrobe-btn" title="Il mio armadio">
    <img src="images/Armadio.png" alt="Armadio">
</a>
<a href="carica_look.php" class="add-look-btn" title="Carica un nuovo look">+</a>

<main class="lookbook-container">
    <div class="style-navigation">
        <button class="style-pill active">Tutti i look</button>
        <button class="style-pill">Streetwear</button>
        <button class="style-pill">Sartoriale</button>
        <button class="style-pill">Minimal</button>
        <button class="style-pill">Sportivo</button>
        <button class="style-pill">Accessori</button>
    </div>

    <div class="gallery-grid">
        <article class="look-card"><div class="look-image-placeholder">FOTO_01</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @m_pigna</div></div></article>
        <article class="look-card" style="background-color: #d1d5db;"><div class="look-image-placeholder">FOTO_02</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @ale_ono</div></div></article>
        <article class="look-card" style="background-color: #e2c9c8;"><div class="look-image-placeholder">FOTO_03</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @simone_dm</div></div></article>
        <article class="look-card" style="background-color: #dcd7d2;"><div class="look-image-placeholder">FOTO_04</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @cosmin_r</div></div></article>
        <article class="look-card" style="background-color: #c5d0d3;"><div class="look-image-placeholder">FOTO_05</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @sara_style</div></div></article>
        <article class="look-card"><div class="look-image-placeholder">FOTO_06</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @luca_fit</div></div></article>
        <article class="look-card" style="background-color: #d1d5db;"><div class="look-image-placeholder">FOTO_07</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @ale_ono</div></div></article>
        <article class="look-card" style="background-color: #e2c9c8;"><div class="look-image-placeholder">FOTO_08</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @simone_dm</div></div></article>
        <article class="look-card" style="background-color: #c5d0d3;"><div class="look-image-placeholder">FOTO_09</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @sara_style</div></div></article>
        <article class="look-card"><div class="look-image-placeholder">FOTO_10</div><div class="look-overlay"><div class="overlay-user"><div class="overlay-mini-avatar"></div> @luca_fit</div></div></article>
    </div>
</main>

<footer>
    &copy; 2026 Fitgram - Be fit, Be Style.<br>
    <div style="margin-top: 10px; font-size: 0.6rem;">Privacy Policy • Termini di Servizio • Contatti</div>
</footer>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-header">
        <h2>Il mio Profilo</h2>
        <button class="close-sidebar-btn" id="close-sidebar">×</button>
    </div>
    <div class="sidebar-content">

        <?php if(isset($_SESSION['utente'])) { ?>

            <div class="sidebar-avatar-large">👤</div>
            <h3 class="sidebar-username">@<?php echo $_SESSION['utente']; ?></h3>
            <p class="sidebar-bio">
                Appassionato di stile e fitness. Sempre alla ricerca del fit perfetto.
            </p>

            <div class="sidebar-stats">
                <div><strong>12</strong><br>Look</div>
                <div><strong>340</strong><br>Follower</div>
                <div><strong>150</strong><br>Seguiti</div>
            </div>

            <a href="modifica_profilo.php" class="edit-profile-btn">
                Modifica le tue informazioni
            </a>

        <?php } else { ?>

            <div class="sidebar-avatar-large">👤</div>

            <h3 class="sidebar-username">Benvenuto su Fitgram</h3>

            <p class="sidebar-bio">
                Accedi o registrati per vedere il tuo profilo, caricare look e seguire altri creator.
            </p>

            <a href="Admin/login.php" class="edit-profile-btn" style="margin-bottom:10px;">
                Accedi
            </a>

            <a href="Admin/registrazione.php" class="edit-profile-btn" style="background-color: var(--accent-dark);">
                Registrati
            </a>

        <?php } ?>

    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profileToggleBtn = document.getElementById('profile-toggle-btn');
        const sidebar = document.getElementById('profile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeBtn = document.getElementById('close-sidebar');

        // Funzione per aprire la sidebar
        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            // Blocca lo scorrimento della pagina dietro quando il pannello è aperto
            document.body.style.overflow = 'hidden';
        }

        // Funzione per chiudere la sidebar
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            // Riabilita lo scorrimento
            document.body.style.overflow = '';
        }

        // Event Listeners
        profileToggleBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        // Chiude la sidebar se clicchi sullo sfondo scuro fuori dal pannello
        overlay.addEventListener('click', closeSidebar);
    });
</script>

</body>
</html>