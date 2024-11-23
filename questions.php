<?php
require_once 'conexao.php';

function getQuestions() {
    $conn = connectDb();
    $query = "SELECT id_pergunta AS id, texto FROM pergunta WHERE status = 'ativa'";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Erro ao buscar perguntas: " . pg_last_error($conn));
    }

    $questions = [];
    while ($row = pg_fetch_assoc($result)) {
        $questions[] = $row;
    }

    pg_close($conn);

    return $questions;
}





?>
