<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitgram – Impostazioni</title>
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

        * { box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--beige-chiaro);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* ── NAVBAR (identica all'index) ── */
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
            color: var(--text-main); padding: 14px 20px; text-decoration: none; display: flex;
            align-items: center; gap: 8px;
            font-size: 0.85rem; border-bottom: 1px solid var(--beige-chiaro); transition: all 0.2s ease;
        }
        .dropdown-menu a:last-child { border-bottom: none; }
        .dropdown-menu a:hover { background-color: var(--beige-chiaro); padding-left: 25px; }
        .dropdown-menu a.settings-link::before { content: '⚙️'; font-size: 0.9rem; }
        .dropdown-menu a.active-page { color: var(--accent-pop); font-weight: 600; }

        .nav-links { flex: 1; display: flex; justify-content: flex-end; align-items: center; gap: 15px; }
        .nav-links a.text-link { text-decoration: none; color: var(--text-main); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .header-profile-link { text-decoration: none; display: flex; align-items: center; margin-left: 10px; cursor: pointer; }
        .header-avatar {
            width: 32px; height: 32px; background-color: var(--beige-chiaro); border: 1px solid var(--rosa-carne);
            border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;
        }
        .header-avatar:hover { background-color: var(--rosa-carne); transform: scale(1.05); }

        /* ── BREADCRUMB ── */
        .breadcrumb {
            max-width: 860px;
            margin: 2rem auto 0;
            padding: 0 1.5rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .breadcrumb a:hover { color: var(--accent-pop); }
        .breadcrumb span { color: var(--accent-pop); font-weight: 500; }

        /* ── WRAPPER IMPOSTAZIONI ── */
        .settings-wrapper {
            max-width: 860px;
            margin: 1.5rem auto 4rem;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 30px;
            align-items: start;
        }

        /* ── SIDEBAR SINISTRA CON SEZIONI ── */
        .settings-nav {
            background-color: var(--pure-white);
            border-radius: 14px;
            border: 1px solid var(--gray-border);
            overflow: hidden;
            position: sticky;
            top: 80px;
        }

        .settings-nav-title {
            padding: 16px 20px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--gray-border);
        }

        .settings-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 20px;
            text-decoration: none;
            font-size: 0.82rem;
            color: var(--text-main);
            border-bottom: 1px solid var(--beige-chiaro);
            transition: all 0.2s ease;
        }
        .settings-nav a:last-child { border-bottom: none; }
        .settings-nav a:hover { background-color: var(--beige-chiaro); color: var(--accent-pop); }
        .settings-nav a.active { background-color: var(--beige-chiaro); color: var(--accent-pop); font-weight: 600; border-left: 3px solid var(--accent-pop); }
        .settings-nav a .nav-icon { font-size: 1rem; width: 20px; text-align: center; }

        /* ── AREA CONTENUTO ── */
        .settings-content { display: flex; flex-direction: column; gap: 24px; }

        /* Titolo pagina */
        .page-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
        }
        .page-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-main);
        }
        .page-header .gear-icon { font-size: 1.5rem; }

        /* Blocco sezione */
        .settings-card {
            background-color: var(--pure-white);
            border-radius: 14px;
            border: 1px solid var(--gray-border);
            overflow: hidden;
        }

        .settings-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--gray-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .settings-card-header h2 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-main);
        }

        .settings-card-header .section-icon { font-size: 1.1rem; }

        .settings-card-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }

        /* Riga impostazione */
        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .setting-row.vertical { flex-direction: column; align-items: flex-start; }

        .setting-label { font-size: 0.85rem; font-weight: 500; color: var(--text-main); margin-bottom: 2px; }
        .setting-desc { font-size: 0.75rem; color: var(--text-muted); line-height: 1.4; }

        /* Input testo */
        .setting-input {
            width: 100%;
            padding: 11px 15px;
            border-radius: 10px;
            border: 1px solid var(--rosa-carne);
            font-family: inherit;
            font-size: 0.85rem;
            background-color: var(--beige-chiaro);
            color: var(--text-main);
            outline: none;
            transition: all 0.3s ease;
            margin-top: 8px;
        }
        .setting-input:focus { background-color: var(--pure-white); border-color: var(--accent-dark); }

        /* Select */
        .setting-select {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid var(--rosa-carne);
            font-family: inherit;
            font-size: 0.82rem;
            background-color: var(--beige-chiaro);
            color: var(--text-main);
            outline: none;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 160px;
        }
        .setting-select:focus { border-color: var(--accent-dark); }

        /* Toggle switch */
        .toggle-switch { position: relative; display: inline-block; width: 46px; height: 26px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: var(--gray-border); border-radius: 26px; transition: 0.3s;
        }
        .toggle-slider:before {
            position: absolute; content: ""; height: 20px; width: 20px;
            left: 3px; bottom: 3px; background-color: white;
            border-radius: 50%; transition: 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }
        .toggle-switch input:checked + .toggle-slider { background-color: var(--accent-pop); }
        .toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }

        /* Divisore leggero */
        .setting-divider { border: none; border-top: 1px solid var(--beige-chiaro); margin: 4px 0; }

        /* Zona pericolosa */
        .danger-zone .settings-card-header { border-bottom-color: #fce4e4; }
        .danger-zone .settings-card-header h2 { color: #c0392b; }
        .danger-zone .settings-card-body { background-color: #fff8f8; }

        .btn-danger {
            padding: 11px 24px;
            background-color: transparent;
            border: 1px solid #e57373;
            color: #c0392b;
            border-radius: 30px;
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .btn-danger:hover { background-color: #c0392b; color: white; border-color: #c0392b; }

        /* Pulsanti azione */
        .settings-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 8px;
        }

        .btn-secondary {
            padding: 11px 24px;
            background-color: transparent;
            border: 1px solid var(--gray-border);
            color: var(--text-muted);
            border-radius: 30px;
            font-family: inherit;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover { border-color: var(--text-muted); color: var(--text-main); }

        .btn-primary {
            padding: 11px 28px;
            background-color: var(--accent-pop);
            border: none;
            color: var(--pure-white);
            border-radius: 30px;
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover { background-color: var(--text-main); }

        /* Toast notifica salvataggio */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background-color: var(--text-main);
            color: var(--pure-white);
            padding: 14px 28px;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 500;
            z-index: 2000;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }
        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* ── SIDEBAR PROFILO (riutilizzata) ── */
        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4); z-index: 1005;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }

        .profile-sidebar {
            position: fixed; top: 0; right: -350px; width: 320px; height: 100vh;
            background-color: var(--pure-white); z-index: 1010;
            box-shadow: -5px 0 25px rgba(0,0,0,0.1);
            transition: right 0.4s cubic-bezier(0.4,0,0.2,1);
            display: flex; flex-direction: column; overflow-y: auto;
        }
        .profile-sidebar.open { right: 0; }

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
            font-size: 0.9rem; transition: background-color 0.3s ease; box-sizing: border-box;
        }
        .edit-profile-btn:hover { background-color: var(--text-main); }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .settings-wrapper { grid-template-columns: 1fr; }
            .settings-nav { position: static; display: flex; overflow-x: auto; border-radius: 10px; }
            .settings-nav-title { display: none; }
            .settings-nav a { border-bottom: none; border-right: 1px solid var(--beige-chiaro); white-space: nowrap; font-size: 0.75rem; padding: 12px 14px; }
            .settings-nav a.active { border-left: none; border-bottom: 3px solid var(--accent-pop); }
            .elegant-tagline { display: none; }
        }

        @media (max-width: 400px) {
            .profile-sidebar { width: 100%; right: -100%; }
        }
    </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav>
    <div class="nav-left">
        <div class="menu-container">
            <div class="hamburger">☰</div>
            <div class="dropdown-menu">
                <a href="#">Esplora Tendenze</a>
                <a href="#">Premium</a>
                <a href="impostazioni.php" class="settings-link active-page">Impostazioni</a>
            </div>
        </div>
        <div class="elegant-tagline">be fit. be style.</div>
    </div>
    <div class="nav-links">
        <a href="Admin/login.php" class="text-link">Accedi</a>
        <a href="Admin/registrazione.php" class="text-link" style="color: var(--accent-dark);">Registrati</a>
        <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo">
            <div class="header-avatar">👤</div>
        </div>
    </div>
</nav>

<!-- ── BREADCRUMB ── -->
<div class="breadcrumb">
    <a href="index.php">🏠 Home</a>
    <span>›</span>
    <span>Impostazioni</span>
</div>

<!-- ── CONTENUTO PRINCIPALE ── -->
<div class="settings-wrapper">

    <!-- Sidebar navigazione sezioni -->
    <nav class="settings-nav">
        <div class="settings-nav-title">Sezioni</div>
        <a href="#profilo" class="active"><span class="nav-icon">👤</span> Profilo</a>
        <a href="#account"><span class="nav-icon">🔐</span> Account & Sicurezza</a>
        <a href="#notifiche"><span class="nav-icon">🔔</span> Notifiche</a>
        <a href="#privacy"><span class="nav-icon">🔒</span> Privacy</a>
        <a href="#aspetto"><span class="nav-icon">🎨</span> Aspetto</a>
        <a href="#pericolo"><span class="nav-icon">⚠️</span> Zona pericolosa</a>
    </nav>

    <!-- Colonna delle impostazioni -->
    <div class="settings-content">

        <div class="page-header">
            <span class="gear-icon">⚙️</span>
            <h1>Impostazioni</h1>
        </div>

        <!-- ── SEZIONE PROFILO ── -->
        <section id="profilo" class="settings-card">
            <div class="settings-card-header">
                <span class="section-icon">👤</span>
                <h2>Profilo Pubblico</h2>
            </div>
            <div class="settings-card-body">

                <div class="setting-row vertical">
                    <div>
                        <div class="setting-label">Nome utente</div>
                        <div class="setting-desc">Il tuo @username visibile a tutti gli utenti</div>
                    </div>
                    <input type="text" class="setting-input"
                           placeholder="@il_tuo_username"
                           value="<?php echo isset($_SESSION['utente']) ? htmlspecialchars($_SESSION['utente']) : ''; ?>">
                </div>

                <hr class="setting-divider">

                <div class="setting-row vertical">
                    <div>
                        <div class="setting-label">Nome visualizzato</div>
                        <div class="setting-desc">Il nome che appare sul tuo profilo</div>
                    </div>
                    <input type="text" class="setting-input" placeholder="Il tuo nome">
                </div>

                <hr class="setting-divider">

                <div class="setting-row vertical">
                    <div>
                        <div class="setting-label">Bio</div>
                        <div class="setting-desc">Racconta qualcosa di te (max 150 caratteri)</div>
                    </div>
                    <textarea class="setting-input" rows="3" maxlength="150"
                              placeholder="Appassionato di stile e fitness..."
                              style="resize: vertical; line-height: 1.5;"></textarea>
                </div>

                <hr class="setting-divider">

                <div class="setting-row vertical">
                    <div>
                        <div class="setting-label">Sito web</div>
                        <div class="setting-desc">Aggiungi un link al tuo sito o portfolio</div>
                    </div>
                    <input type="url" class="setting-input" placeholder="https://tuosito.com">
                </div>

                <div class="settings-actions">
                    <button class="btn-secondary">Annulla</button>
                    <button class="btn-primary" onclick="showToast('Profilo aggiornato ✓')">Salva modifiche</button>
                </div>
            </div>
        </section>

        <!-- ── SEZIONE ACCOUNT & SICUREZZA ── -->
        <section id="account" class="settings-card">
            <div class="settings-card-header">
                <span class="section-icon">🔐</span>
                <h2>Account & Sicurezza</h2>
            </div>
            <div class="settings-card-body">

                <div class="setting-row vertical">
                    <div>
                        <div class="setting-label">Indirizzo email</div>
                        <div class="setting-desc">Usato per accedere e per le notifiche</div>
                    </div>
                    <input type="email" class="setting-input" placeholder="tua@email.com">
                </div>

                <hr class="setting-divider">

                <div class="setting-row vertical">
                    <div>
                        <div class="setting-label">Nuova password</div>
                        <div class="setting-desc">Lascia vuoto se non vuoi cambiarla</div>
                    </div>
                    <input type="password" class="setting-input" placeholder="••••••••">
                </div>

                <div class="setting-row vertical">
                    <div>
                        <div class="setting-label">Conferma nuova password</div>
                    </div>
                    <input type="password" class="setting-input" placeholder="••••••••">
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Autenticazione a due fattori</div>
                        <div class="setting-desc">Aggiungi un livello extra di sicurezza all'account</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-actions">
                    <button class="btn-secondary">Annulla</button>
                    <button class="btn-primary" onclick="showToast('Dati account salvati ✓')">Salva modifiche</button>
                </div>
            </div>
        </section>

        <!-- ── SEZIONE NOTIFICHE ── -->
        <section id="notifiche" class="settings-card">
            <div class="settings-card-header">
                <span class="section-icon">🔔</span>
                <h2>Notifiche</h2>
            </div>
            <div class="settings-card-body">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Nuovi follower</div>
                        <div class="setting-desc">Avvisami quando qualcuno inizia a seguirmi</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Like ai miei look</div>
                        <div class="setting-desc">Notifiche quando qualcuno apprezza un tuo look</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Commenti</div>
                        <div class="setting-desc">Avvisami quando ricevo un nuovo commento</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Newsletter Fitgram</div>
                        <div class="setting-desc">Tendenze, novità e consigli di stile via email</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-actions">
                    <button class="btn-primary" onclick="showToast('Preferenze notifiche salvate ✓')">Salva</button>
                </div>
            </div>
        </section>

        <!-- ── SEZIONE PRIVACY ── -->
        <section id="privacy" class="settings-card">
            <div class="settings-card-header">
                <span class="section-icon">🔒</span>
                <h2>Privacy</h2>
            </div>
            <div class="settings-card-body">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Profilo privato</div>
                        <div class="setting-desc">Solo i tuoi follower potranno vedere i tuoi look</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Chi può commentare i miei look</div>
                        <div class="setting-desc">Scegli chi può lasciarti commenti</div>
                    </div>
                    <select class="setting-select">
                        <option value="tutti">Tutti</option>
                        <option value="follower">Solo follower</option>
                        <option value="nessuno">Nessuno</option>
                    </select>
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Mostra il mio profilo nei suggerimenti</div>
                        <div class="setting-desc">Apparire nei "Creator consigliati" per altri utenti</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-actions">
                    <button class="btn-primary" onclick="showToast('Impostazioni privacy aggiornate ✓')">Salva</button>
                </div>
            </div>
        </section>

        <!-- ── SEZIONE ASPETTO ── -->
        <section id="aspetto" class="settings-card">
            <div class="settings-card-header">
                <span class="section-icon">🎨</span>
                <h2>Aspetto</h2>
            </div>
            <div class="settings-card-body">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Lingua</div>
                        <div class="setting-desc">Lingua dell'interfaccia di Fitgram</div>
                    </div>
                    <select class="setting-select">
                        <option value="it" selected>🇮🇹 Italiano</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="fr">🇫🇷 Français</option>
                        <option value="es">🇪🇸 Español</option>
                    </select>
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Tema scuro</div>
                        <div class="setting-desc">Passa all'interfaccia in modalità notte</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="dark-mode-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-actions">
                    <button class="btn-primary" onclick="showToast('Aspetto aggiornato ✓')">Salva</button>
                </div>
            </div>
        </section>

        <!-- ── ZONA PERICOLOSA ── -->
        <section id="pericolo" class="settings-card danger-zone">
            <div class="settings-card-header">
                <span class="section-icon">⚠️</span>
                <h2>Zona Pericolosa</h2>
            </div>
            <div class="settings-card-body">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Disattiva account</div>
                        <div class="setting-desc">Il tuo profilo sarà nascosto temporaneamente. Potrai riattivarlo in qualsiasi momento.</div>
                    </div>
                    <button class="btn-danger">Disattiva</button>
                </div>

                <hr class="setting-divider">

                <div class="setting-row">
                    <div>
                        <div class="setting-label">Elimina account</div>
                        <div class="setting-desc">Questa azione è irreversibile. Tutti i tuoi look e dati verranno cancellati definitivamente.</div>
                    </div>
                    <button class="btn-danger" onclick="return confirm('Sei sicuro? Questa azione non può essere annullata.')">Elimina</button>
                </div>

            </div>
        </section>

        <!-- Link per tornare alla home -->
        <div style="text-align: center; padding-bottom: 10px;">
            <a href="index.php" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none; transition: color 0.2s;"
               onmouseover="this.style.color='var(--accent-pop)'" onmouseout="this.style.color='var(--text-muted)'">
                ← Torna a Fitgram
            </a>
        </div>

    </div><!-- /settings-content -->
</div><!-- /settings-wrapper -->

<footer style="padding: 2rem; text-align: center; font-size: 0.7rem; letter-spacing: 2px; color: var(--text-muted); text-transform: uppercase; border-top: 1px solid var(--gray-border);">
    &copy; 2026 Fitgram – Be fit, Be Style.
    <div style="margin-top: 10px; font-size: 0.6rem;">Privacy Policy • Termini di Servizio • Contatti</div>
</footer>

<!-- Toast notifica -->
<div class="toast" id="toast"></div>

<!-- Sidebar profilo -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<aside class="profile-sidebar" id="profile-sidebar">
    <div class="sidebar-header">
        <h2>Il mio Profilo</h2>
        <button class="close-sidebar-btn" id="close-sidebar">×</button>
    </div>
    <div class="sidebar-content">
        <?php if(isset($_SESSION['utente'])) { ?>
            <div class="sidebar-avatar-large">👤</div>
            <h3 class="sidebar-username">@<?php echo htmlspecialchars($_SESSION['utente']); ?></h3>
            <p class="sidebar-bio">Appassionato di stile e fitness. Sempre alla ricerca del fit perfetto.</p>
            <div class="sidebar-stats">
                <div><strong>12</strong><br>Look</div>
                <div><strong>340</strong><br>Follower</div>
                <div><strong>150</strong><br>Seguiti</div>
            </div>
            <a href="modifica_profilo.php" class="edit-profile-btn">Modifica le tue informazioni</a>
        <?php } else { ?>
            <div class="sidebar-avatar-large">👤</div>
            <h3 class="sidebar-username">Benvenuto su Fitgram</h3>
            <p class="sidebar-bio">Accedi o registrati per vedere il tuo profilo, caricare look e seguire altri creator.</p>
            <a href="Admin/login.php" class="edit-profile-btn" style="margin-bottom:10px;">Accedi</a>
            <a href="Admin/registrazione.php" class="edit-profile-btn" style="background-color: var(--accent-dark);">Registrati</a>
        <?php } ?>
    </div>
</aside>

<script>
    // ── Sidebar profilo ──
    document.addEventListener('DOMContentLoaded', () => {
        const profileToggleBtn = document.getElementById('profile-toggle-btn');
        const sidebar = document.getElementById('profile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeBtn = document.getElementById('close-sidebar');

        function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); document.body.style.overflow = ''; }

        profileToggleBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // ── Navigazione sezioni attiva ──
        const navLinks = document.querySelectorAll('.settings-nav a');
        const sections = document.querySelectorAll('.settings-content section');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    const id = entry.target.getAttribute('id');
                    const activeLink = document.querySelector(`.settings-nav a[href="#${id}"]`);
                    if (activeLink) activeLink.classList.add('active');
                }
            });
        }, { threshold: 0.4 });

        sections.forEach(s => observer.observe(s));
    });

    // ── Toast notifica ──
    function showToast(msg) {
        const toast = document.getElementById('toast');
        toast.textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2500);
    }
</script>

</body>
</html>