<?php
$host = 'localhost';
$db = 'ifoa_office';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);

$stmt = $pdo->prepare('SELECT * FROM users');
$stmt->execute();
$users = $stmt->fetchAll();

$file_name = 'files/users.csv';
// $file_handle = fopen($file_name, 'w');

// fputcsv($file_handle, array_keys($users[0]));
// foreach ($users as $index => $user){
//     fputcsv($file_handle, $user);
// }
// fclose($file_handle);


$file_handle_imp = fopen($file_name, 'r');
fgetcsv($file_handle_imp);

while (($data = fgetcsv($file_handle_imp)) !== FALSE){
    print_r($data);
    $seEsisteStmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $seEsisteStmt->execute([
        'email' => $data[4],
    ]);
    $giàEsiste = $seEsisteStmt->fetch();
    
    if(!$giàEsiste){
        $stmt = $pdo->prepare('INSERT INTO users (name, surname, age, email, profession) VALUES (:name, :surname, :age, :email, :profession)');
        $stmt->execute([
            'name' => $data[1],
            'surname' => $data[2],
            'age' => $data[3],
            'email' => $data[4],
            'profession' => $data[5],
        ]);
    }
}

fclose($file_handle_imp);



