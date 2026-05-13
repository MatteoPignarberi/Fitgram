<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_logged = isset($_SESSION['user_id']);
$nome_display = $is_logged ? ($_SESSION['nome'] ?? $_SESSION['username']) : "Ospite";

// Capisce se deve aggiungere ../ per uscire dalle cartelle Admin o view
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
            <span class="text-link" style="margin-right: 15px; color: var(--accent-dark);">Ciao, <?php echo htmlspecialchars($nome_display); ?></span>
            <div id="profile-toggle-btn" class="header-profile-link" style="cursor: pointer;">
                <div class="header-avatar">
                    <?php if(!empty($_SESSION['foto_profilo']) && $_SESSION['foto_profilo'] !== 'default_avatar.png'): ?>
                        <img src="<?php echo $base; ?>uploads/<?php echo $_SESSION['foto_profilo']; ?>" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                    <?php else: ?>
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <a href="<?php echo $base; ?>view/login.php" class="text-link">Accedi</a>
            <a href="<?php echo $base; ?>view/registrazione.php" class="text-link" style="color: var(--accent-dark);">Registrati</a>
            <div id="profile-toggle-btn" class="header-profile-link">
                <div class="header-avatar">👤</div>
            </div>
        <?php endif; ?>
    </div>
</nav>

<a href="#" class="wardrobe-btn" title="Il mio armadio">
    <img src="<?php echo $base; ?>resources/Images/Armadio.png" alt="Armadio">
</a>

<a href="<?php echo $is_logged ? $base.'view/carica_look.php' : $base.'view/login.php'; ?>"
   class="add-look-btn"
        <?php if(!$is_logged) echo 'onclick="alert(\'Devi accedere per caricare un look!\');"'; ?>>+</a>