<?php
require 'library/_dao.php';

if (isset($_SESSION['user_id'])) {     // depuis le lien "déconnexion"
    session_destroy();
    header("Location: index.php");

} else {
    $user_id_input = $_POST['user_id'];      // depuis l'inputbox
    $user_pw_input = $_POST['user_pw'];

    $user_data = getUserInfo($user_id_input);  // met les infos de l'utilisateur dans un array


    if (isset($user_data['user_id'])) {
        if (password_verify($user_pw_input, $user_data['password']))   // verif sur le hass BCRYPT dans la db

        {
            $_SESSION['user_id'] = $user_data['user_id'];   // confirmation du user_id
            $_SESSION['user_first_name'] = $user_data['first_name'];    // prénom à afficher en header
            $_SESSION['user_category'] = $user_data['category'];
        }
    }
    header("Location: $_SERVER[HTTP_REFERER]");  // retour à la page précédente

}
