<?php include_once "header.php";
//**************TESTING
//session_start();
// dla testowania konretnego gracza można przypisać poniżej jego id do zmiennej $_SESSION['player_id']
//$_SESSION['player_id'] = 21;
//**************TESTING

?>

    <main>
        <section class="main-section">
            <h1>Connect4</h1>
            <input type="submit" class="btn-main new-game-btn" value="Nowa gra">
            <input type="submit" class="btn-main join-game-btn" value="Dołącz do gry">
        </section>
    </main>

<?php
include_once "templates.html";
include_once "footer.php";
?>