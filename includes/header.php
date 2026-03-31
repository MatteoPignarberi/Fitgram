<?php
/**
 * @var bool $is_logged
 * @var string $username_mostrato
 */
?>
<nav>
    <div class="nav-left">
        <div class="menu-container">
            <div class="hamburger">☰</div>
            <div class="dropdown-menu">
                <a href="#">Esplora Tendenze</a>
                <a href="../Admin/premium.php">Premium</a>
                <?php if ($is_logged): ?>
                    <a href="../admin/impostazioni.php">Impostazioni</a>
                    <a href="../Admin/logout.php">Esci</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="elegant-tagline">be fit. be style.</div>
    </div>

    <div class="search-container">
        <img src="../images/LenteDiIngrandimento.png" alt="Cerca" class="search-icon">
        <input type="text" class="search-bar" placeholder="Cerca stili, capi o creator...">
    </div>

    <div class="nav-links">
        <?php if ($is_logged): ?>
            <span class="text-link" style="margin-right: 15px; color: var(--accent-dark);">Ciao, <?php echo htmlspecialchars($username_mostrato); ?></span>
            <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo" style="cursor: pointer;">
                <div class="header-avatar"><?php echo strtoupper(substr($username_mostrato, 0, 1)); ?></div>
            </div>
        <?php else: ?>
            <a href="../Admin/login.php" class="text-link">Accedi</a>
            <a href="../Admin/registrazione.php" class="text-link" style="color: var(--accent-dark);">Registrati</a>
            <div id="profile-toggle-btn" class="header-profile-link" title="Visualizza Profilo" style="cursor: pointer;">
                <div class="header-avatar">👤</div>
            </div>
        <?php endif; ?>
    </div>
</nav>

<a href="#" class="wardrobe-btn" title="Il mio armadio">
    <img src="images/Armadio.png" alt="Armadio">
</a>
<a href="carica_look.php" class="add-look-btn" title="Carica un nuovo look">+</a>