<?php 
    //Création de la session qui va rediriger l'utilisateur vers la page d'accueil
    session_start();
    if(isset($_SESSION['connect'])){
        header('location: index.php?user_is_connected');exit();
    }

    //bdd connection
    require('src/bddConnection.php');
   
    //véification des champs si remplis
    if(!empty($_POST['email'])&&!empty($_POST['password'])){

        //variable de récupération des données
        $email = $_POST['email'];
        $password = $_POST['password']; 
        $error = 1;

        //hash du mot du mot de passe 
        $password = sha1($password."1256");

        //vérification de l'email de l'utilisareur dans la bdd
        $req = $bdd->prepare('SELECT * FROM users WHERE email = ?');
        $req->execute(array($email));

        //Boucle wile pour la vérification ligne par ligne
        while($data = $req->fetch()){
            if($password == $data['password']){
                $error = 0;
                $_SESSION['connect'] = 1;
                $_SESSION['pseudo'] = $data['pseudo'];

                if(isset($_POST['connect'])) {
                    setcookie('log', $data['secret'], time() + 365*24*3600, '/', '', false, true);
                }
            
                header('location: connection.php?success=1');exit();
            };
           
        }
        if($error == 1 ){
                header('location: connection.php?error=1');exit();
            }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="design/default.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    
<header class="header_co">
            <div class="container text-center text-light p-3">
                <h1>Blue<span class="text-dark">Books</span></h1>
                <p class="fw-bolder">Soutenez les auteurs de romans indépendants en achetant et en commentant leurs œuvres littéraires !</p>
            </div>
</header>
        <div class="container-fluid p-5 my-5 container_center">

            <h2 class="mb-5 text-center">Connectez-vous à <span class="p-1 rounded-1 bg_ogNname"><span class="text-light">Blue</span>Books</span><br>dés maintenant !  </h2>

            <div class="container d-flex flex-column justify-content-center align-items-center">

                <form action="connection.php" method="POST" class="width_md">

                    <div class="container mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" placeholder="Ex : Sonic@gmail.com" class="form-control" required>
                    </div>

                    <div class="container mb-3">

                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" name="password" placeholder="Ex : *********" class="form-control" required>

                        <label class="form-label">
                            <input type="checkbox" name="connect" class="mt-4" checked>
                            <span class="fw-bolder">Connexion automatique</span>
                        </label>
                        <?php 
                            if(isset($_GET['error'])){
                                echo '<p class="text-danger fw-bolder mt-3">Nous ne pouvons pas vous authentifier !</p>';
                            };
                            if(isset($_GET['success'])){
                                echo '<p class="text-success fw-bolder mt-3">Connexion reussie !</p>';
                            };
                        ?>

                        <p class="mt-2">Vous n'avez pas de compte ? <a href="index.php">Inscrivez-vous</a> !</p>  

                    </div>

                    <button type="submit" class="btn btn-primary fs-6"> Connexion</button>

                </form>
            </div>
        </div> 
    <footer class="footer_co"></footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>