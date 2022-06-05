<?php include_once "header.php";
//**************TESTING
//session_start();
// dla testowania konretnego gracza można przypisać poniżej jego id do zmiennej $_SESSION['player_id']
//$_SESSION['player_id'] = 21;
//**************TESTING

?>

    <main>
            <h1>Connect4</h1>
            <form action="">
                <input type="submit" class="btn-main new-game-btn" value="Nowa gra">
            </form>
            <input type="submit" class="btn-main join-game-btn" value="Dołącz do gry">
    </main>

<?php
include_once "templates.html";
include_once "footer.php";
?>