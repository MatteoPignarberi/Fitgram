<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$is_logged = isset($_SESSION['user_id']);
$base = (basename(getcwd()) == 'Admin' || basename(getcwd()) == 'view' || basename(getcwd()) == 'controller') ? '../' : '';
?>
    <nav>
        <div class="nav-links">
            <?php if ($is_logged): ?>
                <span class="text-link">Ciao, <?php echo htmlspecialchars($_SESSION['nome']); ?></span>
                <div class="header-profile-link"><div class="header-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div></div>
            <?php else: ?>
                <a href="<?php echo $base; ?>view/login.php" class="text-link">Accedi</a>
                <a href="<?php echo $base; ?>view/registrazione.php" class="text-link">Registrati</a>
                <div class="header-avatar">👤</div>
            <?php endif; ?>
        </div>
    </nav>

    <a href="#" class="wardrobe-btn"><img src="<?php echo $base; ?>resources/Images/Armadio.png" alt="Armadio"></a>

<?php if ($is_logged): ?>
    <a href="<?php echo $base; ?>view/carica_look.php" class="add-look-btn" title="Carica un nuovo look">+</a>
<?php else: ?>
    <a href="<?php echo $base; ?>view/login.php" class="add-look-btn" title="Accedi per caricare" onclick="return confirm('Devi accedere per caricare un look!');">+</a>
<?php endif; ?>