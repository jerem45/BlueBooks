<?php 
session_start();

require('src/bddConnection.php');

if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password'])&& !empty($_POST['passwordAvailable'])){
 
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordAvailable =$_POST['passwordAvailable'];
  
    if($password != $passwordAvailable){
        header('location: index.php?error=1&pass=1');exit();
    }
      
    $req = $bdd->prepare('SELECT count(*) as numberEmail FROM users WHERE email = ?');
    $req->execute(array($email));
    while($emailVerification = $req->fetch()){
    if($emailVerification['numberEmail'] != 0) {
        header('location: index.php?error=1$email=1');exit();
        }   
    };
    
    $password = sha1($password."1256");
 
    $secret = sha1($email).time();
    $secret = sha1($secret).time().time();

    $req = $bdd->prepare('INSERT INTO users(pseudo,email,password,secret)
                        VALUES (?,?,?,?)
                        ');
    $value = $req->execute(array($pseudo,$email,$password,$secret));
    
    header('location: index.php?success=1');exit();
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
    <header class="header_sub">
    <div class="container text-center text-light p-3">
                <h1>Blue<span class="text-dark">Books</span></h1>
                <p class="fw-bolder">Soutenez les auteurs de romans indépendant en achetant, commentant leur oeuvre littéraire !</p>
            </div>
    </header>
        <div class="container-fluid p-3 my-5 container_center">
            
            <div class="container d-flex flex-column justify-content-center align-items-center">
                
                <?php if(isset($_SESSION['connect'])){?>
                 <?php 
                    echo 'vous étes connecté '.$_SESSION['pseudo'].'<br>';
                    echo '<a href="disconnection.php">Se deconnecter !</a>' 
                    ?>   

                <?php } else { ?>
                   
                    <h2 class="mb-5 text-center">Inscrivez-vous gratuitement à <span class="p-1 rounded-1 bg_ogNname"><span class="text-light">Blue</span>Books</span><br>dés maintenant !  </h2>

                    <form action="index.php" method="POST" class="width_md">
                        <div class="container mb-3">

                            <label for="pseudo" class="form-label">Votre pseudo</label>
                            <input type="text" name="pseudo" placeholder="Ex: Sonic" class="form-control" required>
                        </div>

                        <div class="container mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" placeholder="Ex : Sonic@gmail.com" class="form-control"required>
                        </div>

                        <div class="container mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" placeholder="Ex : *********" class="form-control" required>
                        </div>

                        <div class="container mb-3">
                            <label for="passwordAvailable" class="form-label">Mot de passe (confirmer)</label>
                            <input type="password" name="passwordAvailable" placeholder="Ex : *********" class="form-control"required>

                        <?php
                        //en cas d'erreur
                            if(isset($_GET['error'])){
                                if(isset($_GET['pass'])){
                                echo '<p class="text-danger fw-bolder mt-3">Les mots de passe ne sont pas indentiques ! </p>';  
                                }

                                else if(isset($_GET['error'])){
                                    if(isset($_GET['email'])){
                                        echo "<p class='text-danger fw-bolder mt-3'>L'email à déja été utilisé !</p>";
                                    }
                                }
                            }
                            //en cas de succés
                                if(isset($_GET['success'])){
                                    echo '<span class="text-success fw-bolder ">Félicitation Pour avoir crée votre compte ! a trés bientôt ! </span>';
                                };
                        ?>
                            <p class="mt-4">Vous avez déja un compte ? <a href="connection.php">Connectez-vous</a> !</p>
                        </div>

                        <button type="submit" class="btn btn-primary fs-6">S'inscrire</button>

                </form>

                <?php }?>
            </div>
        </div> 
        
    <footer class="footer_sub"></footer>
    

    






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>