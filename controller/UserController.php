<?php
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Database.php';

class UserController extends Controller
{
    private $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../config.php';
        $this->db = new Database($config['db']);
    }


    public function getUser()
    {
        $stmt = $this->db->query("SELECT * FROM users;");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($users) {
            $this->json($users);
        } else {
            $this->json(["message" => "User Not Found"], 404);
        }
    }


    public function getUserById($id)
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE userId = ?", [$id['id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $this->json($user);
        } else {
            $this->json(["message" => "User Not Found"], 404);
        }
    }

    public function createUser()
    {
        $input = $this->getInput();
        if (isset($input['email']) && !empty($input['password'])) {
            try {
                $this->db->query("INSERT INTO users (email, password) VALUES (?,?)", [$input['email'], $input['password']]);
                $this->json(["message" => "User Created"], 201);
            } catch (PDOException $e) {
                $this->json(["message" => "Database Error: " . $e->getMessage()], 500);
            }
        } else {
            $this->json(["message" => "Invalid Input"], 400);
        }
    }
     public function putUser($id)
    {
        $input = $this->getInput();
        if (!isset($input['avatar'])) {
            $input['avatar'] = null;
        }

        if (isset($input['email']) && isset($input['password']) && isset($id['id']) && is_numeric($id['id'])) {
            try {
                $stmt = $this->db->query("UPDATE users SET email = ?, password = ?, avatar = ? WHERE userId = ?", [
                    $input['email'],
                    $input['password'],
                    $input['avatar'],
                    $id['id']
                ]);

                if ($stmt->rowCount() === 0) {
                    $this->createUser();
                    return;
                }
                $this->json([
                    "message" => "User updated successfully",
                ]);
                return;
            } catch (PDOException $e) {
                $this->json([
                    "message" => "Database error: " . $e->getMessage(),
                ], 500);
                return;
            }
        }
        $this->json([
            "message" => "Invalid input",
        ], 400);
    }

    public function patchUser($id)
    {
        if (isset($id['id']) && is_numeric($id['id'])) {
            $stmt = $this->db->query("SELECT * FROM users WHERE userId = ?", [$id['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                $this->json([
                    "message" => "User not found",
                ], 404);
                return;
            }

            $input = $this->getInput();
            $user = array_merge($user, $input);

            if (!isset($input['avatar'])) {
                $input['avatar'] = null;
            }


            try {
                $stmt = $this->db->query("UPDATE users SET email = ?, password = ?, avatar = ? WHERE userId = ?", [
                    $user['email'],
                    $user['password'],
                    $user['avatar'],
                    $id['id']
                ]);

                if ($stmt->rowCount() === 0) {
                    $this->json([
                        "message" => "User updated  not successfully",
                    ]);
                    return;
                }
                $this->json([
                    "message" => "User updated successfully",
                ]);
                return;
            } catch (PDOException $e) {
                $this->json([
                    "message" => "Database error: " . $e->getMessage(),
                ], 500);
                return;
            }
        }
        $this->json([
            "message" => "Invalid input",
        ], 400);
    }

    public function deleteUser($id)
    {
        if (isset($id['id']) && is_numeric($id['id'])) {
            try {
                $stmt = $this->db->query("DELETE FROM users WHERE userId = ?", [$id['id']]);
                if ($stmt->rowCount() === 0) {
                    $this->json([
                        "message" => "User not found",
                    ], 404);
                    return;
                }
                $this->json(null, 204);
                return;
            } catch (PDOException $e) {
                $this->json([
                    "message" => "Database error: " . $e->getMessage(),
                ], 500);
                return;
            }
        }
        $this->json([
            "message" => "Invalid user ID",
        ], 400);
    }
}
