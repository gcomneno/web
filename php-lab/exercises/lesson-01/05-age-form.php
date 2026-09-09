<?php

declare(strict_types=1);

$age = $_GET['age'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP 1 - Age check</title>
</head>
<body>
<form method="get">
    <label for="age">Age</label>
    <input id="age" name="age" type="number" min="0">
    <button type="submit">Check</button>
</form>

<?php if ($age !== null): ?>
    <?php if ((int) $age >= 18): ?>
        <p>Result: adult</p>
    <?php else: ?>
        <p>Result: minor</p>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
