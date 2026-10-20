<?php

class AdminModel
{
    public function ensureSchema(): void
    {
        db_query("CREATE TABLE IF NOT EXISTS admin (
            admin_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            username VARCHAR(80) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (admin_id),
            UNIQUE KEY uq_admin_username (username)
        ) ENGINE=InnoDB");

        $username = db_query("SHOW COLUMNS FROM admin LIKE 'username'");
        $hasUsername = mysqli_num_rows($username) > 0;
        mysqli_free_result($username);
        if (!$hasUsername) {
            db_query('ALTER TABLE admin ADD username VARCHAR(80) DEFAULT NULL');
        }

        $password = db_query("SHOW COLUMNS FROM admin LIKE 'password_hash'");
        $hasPassword = mysqli_num_rows($password) > 0;
        mysqli_free_result($password);
        if (!$hasPassword) {
            db_query('ALTER TABLE admin ADD password_hash VARCHAR(255) DEFAULT NULL');
        }

        $index = db_query("SHOW INDEX FROM admin WHERE Key_name = 'uq_admin_username'");
        $hasIndex = mysqli_num_rows($index) > 0;
        mysqli_free_result($index);
        if (!$hasIndex) {
            db_query('ALTER TABLE admin ADD UNIQUE KEY uq_admin_username (username)');
        }
    }

    public function all()
    {
        return db_rows(db_query("SELECT admin_id, username FROM admin WHERE username IS NOT NULL AND username <> '' ORDER BY admin_id"));
    }

    public function find($adminId)
    {
        $adminId = db_escape($adminId);
        $rows = db_rows(db_query("SELECT admin_id, username FROM admin WHERE admin_id = '$adminId' LIMIT 1"));
        return $rows[0] ?? null;
    }

    public function save($adminId, $username, $password)
    {
        global $connection;

        $username = db_escape($username);
        $adminId = db_escape($adminId);
        if ($adminId !== '') {
            if ($password !== '') {
                $passwordHash = db_escape(password_hash($password, PASSWORD_DEFAULT));
                $query = "UPDATE admin SET username = '$username', password_hash = '$passwordHash' WHERE admin_id = '$adminId' LIMIT 1";
            } else {
                $query = "UPDATE admin SET username = '$username' WHERE admin_id = '$adminId' LIMIT 1";
            }
        } else {
            $passwordHash = db_escape(password_hash($password, PASSWORD_DEFAULT));
            $query = "INSERT INTO admin (username, password_hash)
                VALUES ('$username', '$passwordHash')";
        }
        return mysqli_query($connection, $query) !== false;
    }

    public function delete($adminId)
    {
        $adminId = db_escape($adminId);
        db_query("DELETE FROM admin WHERE admin_id = '$adminId' LIMIT 1");
        $remaining = db_rows(db_query('SELECT COUNT(*) AS total FROM admin'));
        if ((int) ($remaining[0]['total'] ?? 0) === 0) {
            db_query('ALTER TABLE admin AUTO_INCREMENT = 1');
        }
    }
}
