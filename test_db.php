<?php

try {
    $pdo = new PDO(getenv('DB_DSN'), getenv('DB_USER'), getenv('DB_PASS'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectou ao banco!\n";

    // Cria tabela de teste
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50),
            email VARCHAR(50)
        )
    ");
    echo "Tabela 'users' criada!\n";

    // Insere um registro
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
    $stmt->execute(['name' => 'Fred', 'email' => 'fred@examplo.com']);
    echo "Registro inserido!\n";

    // Lê os registros
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Registros no banco:\n";
    print_r($users);

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
