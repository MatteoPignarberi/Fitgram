<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged = isset($_SESSION['user_id']);
$username_mostrato = $is_logged ? $_SESSION['username'] : "Ospite";

// Gestione dei percorsi relativi
$base = (basename(getcwd()) == 'Admin' || basename(getcwd()) == 'view' || basename(getcwd()) == 'controller') ? '../' : '';
?>
    <nav>
        <div class="nav-left">
            <div class="menu-container">
                <div class="hamburger">☰</div>
                <div class="dropdown-menu">
                    <a href="<?php echo $base; ?>index.php">Esplora Tendenze</a>
                    <a href="<?php echo $base; ?>controller/premiumController.php">Premium</a>
                    <?php if ($is_logged): ?>
                        <a href="<?php echo $base; ?>view/impostazioni.php">Impostazioni</a>
                        <a href="<?php echo $base; ?>controller/logoutController.php">Esci</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="elegant-tagline">be fit. be style.</div>
        </div>

        <div class="search-container">
            <img src="<?php echo $base; ?>resources/Images/LenteDiIngrandimento.png" alt="Cerca" class="search-icon">
            <input type="text" class="search-bar" placeholder="Cerca stili, capi o creator...">
        </div>

        <div class="nav-links">
            <?php if ($is_logged): ?>
                <span class="text-link" style="margin-right: 15px; color: var(--accent-dark);">
                Ciao, <?php echo htmlspecialchars($_SESSION['nome'] ?? $username_mostrato); ?>
            </span>
                <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo" style="cursor: pointer;">
                    <div class="header-avatar">
                        <?php
                        // Prendiamo la foto dalla sessione aggiornata dal controller
                        $foto_sessione = $_SESSION['foto_profilo'] ?? '';
                        if (!empty($foto_sessione) && $foto_sessione !== 'default_avatar.png'): ?>
                            <img src="<?php echo $base; ?>uploads/<?php echo $foto_sessione; ?>?v=<?php echo time(); ?>"
                                 style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <?php echo strtoupper(substr($username_mostrato, 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo $base; ?>view/login.php" class="text-link">Accedi</a>
                <a href="<?php echo $base; ?>view/registrazione.php" class="text-link" style="color: var(--accent-dark);">Registrati</a>
                <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo" style="cursor: pointer;">
                    <div class="header-avatar">👤</div>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <a href="../controller/armadioController.php" class="wardrobe-btn" title="Il mio armadio">
        <img src="../images/Armadio.png" alt="Armadio">
    </a>

<?php if ($is_logged): ?>
    <a href="<?php echo $base; ?>view/carica_look.php" class="add-look-btn" title="Carica un nuovo look">+</a>
<?php else: ?>
    <a href="<?php echo $base; ?>view/login.php" class="add-look-btn" title="Accedi per caricare" onclick="return confirm('Devi essere loggato per caricare un look. Vai al login?');">+</a>
<?php endif; ?>