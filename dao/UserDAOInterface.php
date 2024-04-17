<?php
interface UserDAOInterface
{
    public function buildUser($data);
    public function create(User $user, $authUser = false);
    public function update(User $user, $redirect = true);
    public function findByToken($token);
    public function verifyToken($token = false);
    public function setTokenToSession($token, $redirect = true);
    public function autenticateUser($email, $password);
    public function findByEmail($email);
    public function findById($id);
    public function changePassword(User $user);
    public function destroyToken();
}
