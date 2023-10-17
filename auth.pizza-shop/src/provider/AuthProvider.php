<?php

namespace pizzashop\auth\api\provider;

use pizzashop\auth\api\entities\User;

class AuthProvider
{
//    programmation d'un mécanisme d'authentification à base de jeton
//JWT pour l'application pizza-shop.
//Ce mécanisme réalise uniquement l'authentification : les utilisateurs fournissent leurs credentials et
//reçoivent en retour un token JWT attestant leur identité.
//Ce token peut ensuite être joint à leurs requêtes sur l'api.

//Programmer le provider d'authentification. Il s'agit d'une classe fournissant les fonctionnalités de
//base de l'authentification et qui interagit avec la base de données des utilisateurs. Elle offre une
//interface permettant :

    public function checkCredentials(string $user, string $pass): void
    {
        // vérifier les credentials d'un utilisateur
        // si les credentials sont valides, générer un token JWT et le retourner
        // si les credentials ne sont pas valides, retourner une erreur

        try {
            $user = User::where('email', $user)->firstOrFail();
            if (!is_null($user)) throw new \Exception("User already exists");
        } catch (\Exception) {
            if (!password_verify($pass, $user->password)) throw new \Exception("Invalid password");
            $this->generateRefreshToken($user->id);
        }
    }

    public function checkToken(string $token): void
    {
        // vérifier la validité d'un token
        // si le token est valide, retourner l'id de l'utilisateur
        // si le token n'est pas valide, retourner une erreur


    }

    public function register(string $user, string $pass): void
    {
        // enregistrer un nouvel utilisateur
        // si l'utilisateur existe déjà, retourner une erreur
        // si l'utilisateur n'existe pas, créer un nouvel utilisateur et retourner un token JWT

        try {
            $user = User::where('email', $user)->firstOrFail();
            if (!is_null($user)) throw new \Exception("User already exists");
        } catch (\Exception) {
            $user = new User();
            $user->email = $user;
            $user->password = password_hash($pass, PASSWORD_DEFAULT);
            $user->save();
            $this->generateRefreshToken($user->id);
        }
    }

    public function activate(string $token): void
    {
        // activer un utilisateur
        // si le token est valide, activer l'utilisateur et retourner un token JWT
        // si le token n'est pas valide, retourner une erreur

        try {
            $user = User::where('activation_token', $token)->firstOrFail();
            if (!is_null($user)) throw new \Exception("User already exists");
        } catch (\Exception) {
            $user->active = true;
            $user->save();
            $this->generateRefreshToken($user->id);
        }
    }

    public function resetPassword(string $token, string $old_pass, string $new_pass) : void
    {
        // réinitialiser le mot de passe d'un utilisateur
        // si le token est valide, réinitialiser le mot de passe et retourner un token JWT
        // si le token n'est pas valide, retourner une erreur

        try {
            $user = User::where('reset_passwd_token', $token)->firstOrFail();
            if (!is_null($user)) throw new \Exception("User already exists");
        } catch (\Exception) {
            if (!password_verify($old_pass, $user->password)) throw new \Exception("Invalid password");
            $user->password = password_hash($new_pass, PASSWORD_DEFAULT, ['cost' => 12]);
            $user->save();
            $this->generateRefreshToken($user->id);
        }
    }
}