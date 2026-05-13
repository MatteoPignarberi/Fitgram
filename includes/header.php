<?php
// Se la sessione non è già partita, la facciamo partire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controlliamo se l'utente è loggato guardando la sessione reale
$is_logged = isset($_SESSION['user_id']);
$username_mostrato = $is_logged ? $_SESSION['username'] : "Ospite";
$nome_reale = $is_logged ? ($_SESSION['nome'] ?? $_SESSION['username']) : "Ospite";

// Logica per i percorsi dinamici (gestisce la differenza tra index e sottocartelle)
$base_path = (basename(getcwd()) == 'Admin' || basename(getcwd()) == 'view' || basename(getcwd()) == 'controller') ? '../' : '';
?>
    <nav>
        <div class="nav-left">
            <div class="menu-container">
                <div class="hamburger">☰</div>
                <div class="dropdown-menu">
                    <a href="<?php echo $base_path; ?>index.php">Esplora Tendenze</a>
                    <a href="<?php echo $base_path; ?>controller/premiumController.php">Premium</a>
                    <?php if ($is_logged): ?>
                        <a href="<?php echo $base_path; ?>view/impostazioni.php">Impostazioni</a>
                        <a href="<?php echo $base_path; ?>controller/logoutController.php">Esci</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="elegant-tagline">be fit. be style.</div>
        </div>

        <div class="search-container">
            <img src="<?php echo $base_path; ?>resources/Images/LenteDiIngrandimento.png" alt="Cerca" class="search-icon">
            <input type="text" class="search-bar" placeholder="Cerca stili, capi o creator...">
        </div>

        <div class="nav-links">
            <?php if ($is_logged): ?>
                <span class="text-link" style="margin-right: 15px; color: var(--accent-dark);">Ciao, <?php echo htmlspecialchars($nome_reale); ?></span>
                <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo" style="cursor: pointer;">
                    <div class="header-avatar">
                        <?php
                        // Se hai una foto profilo la mettiamo, altrimenti l'iniziale
                        if (!empty($_SESSION['foto_profilo']) && $_SESSION['foto_profilo'] !== 'default_avatar.png'): ?>
                            <img src="<?php echo $base_path; ?>uploads/<?php echo $_SESSION['foto_profilo']; ?>" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <?php echo strtoupper(substr($username_mostrato, 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo $base_path; ?>view/login.php" class="text-link">Accedi</a>
                <a href="<?php echo $base_path; ?>view/registrazione.php" class="text-link" style="color: var(--accent-dark);">Registrati</a>
                <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo" style="cursor: pointer;">
                    <div class="header-avatar">👤</div>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <a href="#" class="wardrobe-btn" title="Il mio armadio">
        <img src="<?php echo $base_path; ?>resources/Images/Armadio.png" alt="Armadio">
    </a>

<?php if ($is_logged): ?>
    <a href="<?php echo $base_path; ?>view/carica_look.php" class="add-look-btn" title="Carica un nuovo look">+</a>
<?php endif; ?>