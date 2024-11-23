<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o usuário é admin
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

require_once 'questions.php';

$questions = getQuestions();

if (empty($questions)) {
    die("Nenhuma pergunta está disponível no momento.");
}

// Inicializa o índice da pergunta e respostas
if (!isset($_SESSION['current_index'])) {
    $_SESSION['current_index'] = 0;
    $_SESSION['responses'] = [];
}

$currentIndex = $_SESSION['current_index'];
$currentQuestion = $questions[$currentIndex];

// Processa o formulário de avaliação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionId = $_POST['question_id'];
    $score = $_POST['score'];
    $feedback = $_POST['feedback'] ?? '';

    $_SESSION['responses'][$questionId] = [
        'score' => $score,
        'feedback' => $feedback
    ];

    // Avança para a próxima pergunta ou finaliza
    if ($currentIndex + 1 < count($questions)) {
        $_SESSION['current_index']++;
        header("Location: index.php");
        exit;
    } else {
        header("Location: enviar.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Avaliação</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo(a), <?= htmlspecialchars($_SESSION['username']) ?>!</h1>

        <!-- Link para o Painel de Administração (somente admin) -->
        <?php if ($isAdmin): ?>
            <p><a href="admin.php" class="admin-link">Acessar Painel de Administração</a></p>
        <?php endif; ?>

        <!-- Sistema de Avaliação -->
        <h2>Avaliação de Satisfação</h2>
        <form method="POST">
            <h2><?= htmlspecialchars($currentQuestion['texto']) ?></h2>
            <input type="hidden" name="question_id" value="<?= htmlspecialchars($currentQuestion['id']) ?>">
            <div class="scale">
                <?php for ($i = 0; $i <= 10; $i++): ?>
                    <label class="scale-item">
                        <input type="radio" name="score" value="<?= $i ?>" required>
                        <span class="circle"><?= $i ?></span>
                    </label>
                <?php endfor; ?>
            </div>
            <textarea name="feedback" placeholder="Deixe seu feedback (opcional)" rows="4"></textarea>
            <button type="submit">Próxima</button>
        </form>

        <!-- Link para logout -->
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
