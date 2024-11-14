<?php
function generateSession($user, PDO $db)
{
    $token = generateNewToken($db);

    $stmt = $db->prepare("INSERT INTO access (id_user,token,date) VALUES (?,?,curdate())");
    $stmt->execute([$user, $token]);
    if ($db->lastInsertId() > 0) {
        return $token;
    } else {
        throw new Exception('Failed to generate session');
    }
}

function generateNewToken(PDO $db)
{
    if ($db === null) {
        throw new PDOException('Database connection is null');
    }

    $attempts = 0;
    $maxAttempts = 100;

    do {
        $token = md5(uniqid(rand(), true));
        $attempts++;
        if ($attempts > $maxAttempts) {
            throw new Exception('Exceeded maximum attempts to generate a unique token');
        }
    } while (checkToken($token, $db));

    return $token;
}

function checkToken($token, PDO $db)
{
    $sql = "SELECT * FROM access WHERE token = :token";
    $stmt = $db->prepare($sql);
    $stmt->execute(['token' => $token]);
    $result = $stmt->fetch();
    if (!$result)
        return false;
    else
        return true;
}
