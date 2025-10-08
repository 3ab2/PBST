<?php
require_once 'functions.php';
$page_title = $translations['history_title'];
include 'templates/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="text-center mb-4">
        <img src="/pbst_app/images/bst.png" alt="Institution Logo" class="mb-3" style="max-width: 150px;">
        <h1 class="display-4" style="color: var(--military-gold); font-weight: 700;"><?php echo htmlspecialchars($translations['history_title']); ?></h1>
        <p class="lead" style="color: var(--military-secondary); font-weight: 500;">
            <?php echo htmlspecialchars($translations['history_subtitle']); ?>
        </p>
    </div>

    <section class="mb-5">
        <h2 class="mb-3" style="color: var(--military-primary); font-weight: 600;"><?php echo htmlspecialchars($translations['origins_title']); ?></h2>
        <p style="font-size: 1.1rem; color: var(--body-color); line-height: 1.6;">
            <?php echo htmlspecialchars($translations['origins_text']); ?>
        </p>
    </section>

    <section class="mb-5">
        <h2 class="mb-3" style="color: var(--military-primary); font-weight: 600;"><?php echo htmlspecialchars($translations['mission_title']); ?></h2>
        <p style="font-size: 1.1rem; color: var(--body-color); line-height: 1.6;">
            <?php echo htmlspecialchars($translations['mission_text']); ?>
        </p>
    </section>

    <section class="mb-5">
        <h2 class="mb-3" style="color: var(--military-primary); font-weight: 600;"><?php echo htmlspecialchars($translations['values_title']); ?></h2>
        <ul style="font-size: 1.1rem; color: var(--body-color); line-height: 1.6; list-style-type: none; padding-left: 0;">
            <li><?php echo htmlspecialchars($translations['values_discipline']); ?></li>
            <li><?php echo htmlspecialchars($translations['values_honor']); ?></li>
            <li><?php echo htmlspecialchars($translations['values_courage']); ?></li>
            <li><?php echo htmlspecialchars($translations['values_loyalty']); ?></li>
            <li><?php echo htmlspecialchars($translations['values_excellence']); ?></li>
        </ul>
    </section>

    <section class="mb-5">
        <h2 class="mb-3" style="color: var(--military-primary); font-weight: 600;"><?php echo htmlspecialchars($translations['structure_title']); ?></h2>
        <p style="font-size: 1.1rem; color: var(--body-color); line-height: 1.6;">
            <?php echo htmlspecialchars($translations['structure_text']); ?>
        </p>
    </section>

    <section class="mb-5">
        <h2 class="mb-3" style="color: var(--military-primary); font-weight: 600;"><?php echo htmlspecialchars($translations['achievements_title']); ?></h2>
        <p style="font-size: 1.1rem; color: var(--body-color); line-height: 1.6;">
            <?php echo htmlspecialchars($translations['achievements_text']); ?>
        </p>
    </section>

    <section class="mb-5">
        <h2 class="mb-3" style="color: var(--military-primary); font-weight: 600;"><?php echo htmlspecialchars($translations['role_title']); ?></h2>
        <p style="font-size: 1.1rem; color: var(--body-color); line-height: 1.6;">
            <?php echo htmlspecialchars($translations['role_text']); ?>
        </p>
    </section>
</div>

<?php include 'templates/footer.php'; ?>
