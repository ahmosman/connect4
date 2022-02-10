<?php include_once "header.php";
//**************TESTING
//session_start();
// dla testowania konretnego gracza można przypisać poniżej jego id do zmiennej $_SESSION['player_id']
//$_SESSION['player_id'] = 7;
//**************TESTING

?>

    <section>
        <div class="main-div">
            <h1>Saper competitive</h1>
            <form action="">
                <input type="submit" class="btn-main new-game-btn" value="Nowa gra">
            </form>
            <input type="submit" class="btn-main join-game-btn" value="Dołącz do gry">
        </div>
    </section>

<?php
include_once "templates.html";
include_once "footer.php";
?>