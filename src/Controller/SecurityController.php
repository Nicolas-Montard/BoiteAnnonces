<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/login/github', name: 'login_github')]
    public function loginGithub(): RedirectResponse
    {
        $clientId = $_ENV['GITHUB_CLIENT_ID'];
        $redirectUri = $_ENV['GITHUB_REDIRECT_URI'];
        $scope = 'user:email';

        $url = 'https://github.com/login/oauth/authorize' .
            '?client_id=' . $clientId .
            '&redirect_uri=' . urlencode($redirectUri) .
            '&scope=' . $scope;

        return $this->redirect($url);
    }

    #[Route('/login/github/callback', name: 'login_github_callback')]
    public function githubCallback(Request $request)
    {
        $clientId = $_ENV['GITHUB_CLIENT_ID'];
        $clientSecret = $_ENV['GITHUB_CLIENT_SECRET'];
        $redirectUri = $_ENV['GITHUB_REDIRECT_URI'];
        $code = $request->query->get('code');  // Le code de redirection fourni par GitHub

        if (!$code) {
            throw new AuthenticationException('GitHub authentication failed.');
        }

        $client = new Client();
        $response = $client->post('https://github.com/login/oauth/access_token', [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code,
                'redirect_uri' => $redirectUri
            ],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        // Si on obtient un token d'accès, récupérer les données de l'utilisateur
        if (isset($data['access_token'])) {
            $accessToken = $data['access_token'];

            // Utiliser le token pour récupérer les données de l'utilisateur via l'API GitHub
            $userResponse = $client->get('https://api.github.com/user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json'
                ]
            ]);

            $userData = json_decode($userResponse->getBody()->getContents(), true);

            // Récupérer l'email de l'utilisateur
            $email = $userData['email'] ?? null;
            $githubId = $userData['id'];
            $name = $userData['name'] ?? null;

            // Séparer le prénom et le nom, si le champ "name" est disponible et contient un espace
            $firstName = null;
            $lastName = null;
            if ($name) {
                $nameParts = explode(' ', $name, 2); // Divise le nom complet en deux parties
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? null; // Le nom de famille est optionnel
            }

            // Exemple de redirection ou création/connexion de l'utilisateur
            // Tu peux ici soit créer un nouvel utilisateur ou récupérer un utilisateur existant et l'authentifier
            return $this->redirectToRoute('dashboard'); // À adapter selon ton application
        }

        throw new AuthenticationException('GitHub authentication failed.');
    }
}
